<?php

namespace App\Controller;

use App\Entity\Permission;
use App\Form\PermissionType;
use App\Entity\UserGroup;
use App\Repository\PermissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/permission')]
class PermissionController extends AbstractController
{
    #[Route('/', name: 'permission_index', methods: ['GET'])]
    public function index(PaginatorInterface $paginator, Request $request,PermissionRepository $permissionRepository): Response
    {
        


        $queryBuilder =  $permissionRepository->getQuery($request->query->get('search'));
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('permission/index.html.twig', [
            'permissions' => $data,
        ]);

    }

    #[Route('/new', name: 'permission_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $permission = new Permission();
        $form = $this->createForm(PermissionType::class, $permission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($permission);
            $entityManager->flush();

            return $this->redirectToRoute('permission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('permission/new.html.twig', [
            'permission' => $permission,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'permission_show', methods: ['GET'])]
    public function show(Permission $permission): Response
    {
        return $this->render('permission/show.html.twig', [
            'permission' => $permission,
        ]);
    }

    #[Route('/{id}/edit', name: 'permission_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Permission $permission, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PermissionType::class, $permission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('permission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('permission/edit.html.twig', [
            'permission' => $permission,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'permission_delete', methods: ['POST'])]
    public function delete(Request $request, Permission $permission, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$permission->getId(), $request->request->get('_token'))) {
            $entityManager->remove($permission);
            $entityManager->flush();
        }

        return $this->redirectToRoute('permission_index', [], Response::HTTP_SEE_OTHER);
    }
}
