<?php
namespace App\Controller;
use App\Entity\UserGroup;
use App\Entity\Permission;
use App\Form\UserGroupType;
use App\Helper\Constants;
use App\Repository\PermissionRepository;
use App\Repository\UserGroupRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user-group')]
class UserGroupController extends AbstractController
{
    #[Route('/', name: 'user_group_index', methods: ['GET'])]
    public function index(UserGroupRepository $userGroupRepository): Response
    {
        return $this->render('user_group/index.html.twig', [
            'user_groups' => $userGroupRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'user_group_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $userGroup = new UserGroup();
        $form = $this->createForm(UserGroupType::class, $userGroup);
        $form->handleRequest($request);
        $userGroup->setIsActive(true);
        $userGroup->setUpdatedBy($this->getUser());
        $userGroup->setUpdatedAt( new \DateTime());

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userGroup);
            $entityManager->flush();

            return $this->redirectToRoute('user_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_group/new.html.twig', [
            'user_group' => $userGroup,
            'form' => $form,
        ]);
    }
          /**
     * @Route("/{id}/permission", name="user_group_permission", methods={"GET","POST"})
     */
    public function permission(UserGroup $userGroup,Request $request,PermissionRepository $permissionRepository){
        
       // $this->denyAccessUnlessGranted('ad_prmsn_to_grp');

       if($request->request->get('usergrouppermission')){
           $permissions = $permissionRepository->findAll();
              foreach ($permissions as $permission) {
            $userGroup->removePermission($permission);
           }

           $permissions=$permissionRepository->findBy(['id'=>$request->request->get('permission')]);
           foreach ($permissions as $permission) {
              
            $userGroup->addPermission($permission);
           }
         
           $userGroup->setUpdatedAt(new \DateTime());
           $userGroup->setUpdatedBy($this->getUser());
           $this->getDoctrine()->getManager()->flush();
       }
        return $this->render('user_group/show.html.twig', [
            'user_group' => $userGroup,
            'permissions' => $permissionRepository->findForUserGroup($userGroup->getPermissions()),
           
        ]);
 

}

      /**
     * @Route("/{id}/activate", name="user_group_action", methods={"POST"})
     */
    public function action(UserGroup $userGroup,Request $request){
        $this->denyAccessUnlessGranted('edt_usr_grp');

        $userGroup->setIsActive($request->request->get('activateUserGroup'));
        $userGroup->setUpdatedAt(new \DateTime());
        $userGroup->setUpdatedBy($this->getUser());
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success'," User group successfully updated");
        return $this->redirectToRoute('user_group_index');
 


    }        
      /**
     * @Route("/{id}/users", name="user_group_users")
     */
    public function user(UserGroup $userGroup,Request $request,UserRepository $userRepository){
     $this->denyAccessUnlessGranted('ad_usr_to_grp');
       if($request->request->get('usergroupuser')){
           $users = $userGroup->getUsers();
              foreach ($users as $user) {
            $userGroup->removeUser($user);
            }
           
           $users=$userRepository->findBy(['id'=>$request->request->get('user')]);
           foreach ($users as $user) {
            $userGroup->addUser($user);
           }
        //    $userGroup->setUpdatedAt(new \DateTime());
        //    $userGroup->setUpdatedBy($this->getUser());
           $this->getDoctrine()->getManager()->flush();
       }
   // dd($userRepository->findForUserGroup($userGroup->getUsers()));
        return $this->render('user_group/add.user.html.twig', [
            'user_group' => $userGroup,
            'users' => $userRepository->findForUserGroup($userGroup->getUsers()),
           
        ]);
        }

    // #[Route('/{id}', name: 'user_group_show', methods: ['GET'])]
    // public function show(UserGroup $userGroup): Response
    // {
    //     return $this->render('user_group/show.html.twig', [
    //         'user_group' => $userGroup,
    //     ]);
    // }

    #[Route('/{id}/edit', name: 'user_group_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserGroup $userGroup): Response
    {
        $form = $this->createForm(UserGroupType::class, $userGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_group/edit.html.twig', [
            'user_group' => $userGroup,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'user_group_delete', methods: ['POST'])]
    public function delete(Request $request, UserGroup $userGroup): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userGroup->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($userGroup);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_group_index', [], Response::HTTP_SEE_OTHER);
    }
}
