<?php
namespace App\Controller;
use App\Entity\User;
use App\Form\UserType;
use App\Helper\Constants;
//use App\Helper\Constants;
use App\Repository\UserRepository;
use App\Repository\UserGroupRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\AtLeastOneOf;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeImmutableToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


#[Route('/user')]
// #[IsGranted('ROLE_ADMIN')]
class UserController extends AbstractController
{
    #[Route('/', name: 'user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository,PaginatorInterface $paginator, Request $request): Response
    {
   
    // dd($this->getUser()->getLastLogin());

    $queryBuilder = $userRepository->getQuery($request->query->get('search'));
        $data = $paginator->paginate($queryBuilder, 
          $request->query->getInt('page', 1),
            10
         );

        return $this->render('user/index.html.twig', [
            'users' => $data,
            'getTotalItemCount'=> $userRepository->total_users()
        ]);
    }
    
    #[Route('/new', name: 'user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $roles = (array)$request->request->get('user')['roles'];

            $user->setRoles($roles);
            $user->setStatus(Constants::OFFLINE);
            $user->setCreatedAt(new \DateTime);
            $user->setUserName($this->get_username($user->getFirstName(), $user->getFatherName()));

            $password = $this->randomPassword();

            $user->setPassword($hasher->hashPassword($user, $password));

            $user->setRegisteredBy($this->getUser());
            $user->setIsOnline(false);
           // $user->setPhoto(null);
           // $user->setPhone($request->request->get('phone'));
            $user->setIsActive(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
         //  $this->addFlash('success', "User Registered");
            $pdfOptions = new Options();
            $pdfOptions->set('defaultFont', 'Arial');

            // Instantiate Dompdf with our options
            $dompdf = new Dompdf($pdfOptions);

            // Retrieve the HTML generated in our twig file
            $html = $this->renderView('user/print.creadentials.html.twig', [
                'user' => $user,
                "password" => $password
            ]);

            // Load HTML to Dompdf
            $dompdf->loadHtml($html);

            // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
            $dompdf->setPaper('A4', 'portrait');

            // Render the HTML as PDF
            $dompdf->render();

            // Output the generated PDF to Browser (force download)
            $dompdf->stream($user->getFirstName() . ".pdf", [
                "Attachment" => true
            ]);


            $this->addFlash('success','User successfully registered');

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
           // 'user' => $user,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/resetPassword', name: 'reset_password', methods: ['GET', 'POST'])]
    public function resetPassword(User $user,Request $request, UserPasswordHasherInterface $hasher): Response
    {
       //  $this->denyAccessUnlessGranted('rst_pswd');
        $username = $this->get_username($user->getFirstName(),$user->getFatherName());

        $password = $this->randomPassword();

        $user->setPassword($hasher->hashPassword($user, $password));
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('user_show', [], Response::HTTP_SEE_OTHER);
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);
        $html = $this->renderView('user/print.creadentials.html.twig', [
            'fname' => $user->getFirstName(),
            'lname' => $user->getLastName(),
            'role' => $user->getRoles(),
            'username'=> $username,
            "password" => $password

           ]);

             $dompdf->loadHtml($html);
             $dompdf->setPaper('A4', 'portrait');
             $dompdf->render();
             $dompdf->stream($user->getFirstName() . ".pdf", [
                 "Attachment" => true
             ]);
             
    
    }
    #[Route('/{id}', name: 'user_show', methods: ['GET'])]
    public function show(User $user, Request $request): Response
    {
        if ($request->query->get('me')) {

            $user = $this->getUser();
        }
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }
    // if (!$this->getUser())
    // return $this->redirectToRoute('app_login');
/////////////////////////////////////////////////////////////////////////////////////
    #[Route('/profile', name: 'user_profile', methods: ['GET','POST'])]
    public function upload_profile(Request $request): Response
    {
        if (!$this->getUser())
            return $this->redirectToRoute('app_login');

        $imageform = $this->createFormBuilder()
            ->add('ImageFile', FileType::class, array(
                'attr' => array(
                    'id' => 'filePhoto',
                    'class' => 'sr-only',
                    'accept' => 'image/jpeg,image/png,image/jpg'
                ),
                'label' => '',


            ))
            ->getForm();
        $imageform->handleRequest($request);
        if ($imageform->isSubmitted() && $imageform->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $uploadedFile = $imageform['ImageFile']->getData();
            $destination = $this->getParameter('kernel.project_dir') . '/public/profile';
            $newFilename = $this->getUser()->getId() . uniqid() . '.' . $uploadedFile->getClientOriginalExtension();
            $uploadedFile->move($destination, $newFilename);

            $user = $this->getUser()->getUserInfo();
            $user->setPhoto($newFilename);
            $em->flush();
        }

        return $this->render('user/show.html.twig', [
            'user' => $this->getUser(),
            'photoform' => $imageform->createView()
        ]);
    }
//////////////////////////////////////////////////
    #[Route('/print/{id}', name: 'user_info_print', methods: ['GET', 'POST'])]
    public function Print(Request $request,User $user, UserPasswordHasherInterface $hasher): Response
    {
        //  $this->denyAccessUnlessGranted('rst_pswd');
      //  $user = $user->getRoles();
        if (in_array("ROLE_SUPERADMIN", $user->getRoles())) {
            $this->addFlash('warning', "Cannot Print Adminstrator Credential!!! ");
            return  $this->redirectToRoute("user_index");
        }
        $password = $this->randomPassword();
        $user->setPassword($hasher->hashPassword($user, $password));
        $this->getDoctrine()->getManager()->flush();
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($pdfOptions);

        $res = $this->renderView('user/print.creadentials.html.twig', [
            "user" => $user, 
            'password' => $password,
            'phone'=>$user->getPhone(),
            'email'=>$user->getEmail(),
            'sex'=>$user->getSex()

        
        
        ]);
        //$date = new DateTime('now');
        $dompdf->loadHtml($res);
        $dompdf->setPaper('A5', 'Portrait');

        // Render the HTML as PDF
        $dompdf->render();
        $dompdf->stream($user->getFirstName() . ".pdf", [
            "Attachment" => true
        ]);
    }


        /**
     * @Route("/{id}/activate", name="user_action", methods={"POST"})
     */
    public function action(User $user, Request $request)
    {
        //$this->denyAccessUnlessGranted('edt_usr');
        $user->setIsActive($request->request->get('activateUser'));
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('user_info_index');
    }


    #[Route('/{id}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // $user->setStatus(Constants::OFFLINE);
            // $user->setCreatedAt(new \DateTime);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', "User updated");

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

     /**
     * @Route("/checkUsername", name="check_username", methods={"GET"})
     */
    public function checkUsername(Request $request, UserRepository $userRepository)
    {
        $status = $userRepository->findOneBy(['username' => $request->query->get('username')]) ? true : false;
        return new JsonResponse($status);
    }

    #[Route('/changePassword/{id}', name: 'change_password', methods: ['GET', 'POST'])]
    public function changePassword(User $user,Request $request, UserPasswordHasherInterface $hasher)
    {

        if (!$this->getUser()->getLastLogin()) {

            return $this->redirectToRoute('my_profile');
        }
        $form = $this->createFormBuilder()
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'constraints' => [new Assert\Regex(['pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$/', 'message' => "The password must contain atleast number, capital letter and small letter"]), new Length(['min' => 8])],
                'first_options'  => ['label' => 'New Password'],
                'second_options' => ['label' => 'Confirm Password'],
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();
            $password = $form->getData()['newPassword'];
            $user->setPassword($hasher->hashPassword($user, $password));

            $user->setLastLogin(new \DateTimeImmutable());
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            //end session and redirect to logout
            // $this->redirectToRoute('app_logout');

            $this->get('security.token_storage')->setToken(null);
            $this->get('session')->invalidate();
            $this->addFlash('success', "Password Successfully changed,please login!");
            return $this->redirectToRoute('app_login');
        }
        return $this->render('user/password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/Groups/", name="add_user_group", methods={"POST"})
     */

    public function groups(User $user, Request $request, UserGroupRepository $userGroupRepository)
    {
     //   $this->denyAccessUnlessGranted('ad_usr_t_grp');
        if ($request->request->get('usergroup')) {
            $userGroups = $userGroupRepository->findAll();
            foreach ($userGroups as $userGroup) {
                $user->removeUserGroup($userGroup);
            }
            $userGroups = $userGroupRepository->findBy(['id' => $request->request->get('usergroups')]);

            foreach ($userGroups as $userGroup) {
                $user->addUserGroup($userGroup);
            }
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }


    #[Route('/{id}', name: 'user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }
        $this->addFlash('success', "User deleted");

        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    }

    function get_username($first, $middle)
    {
        $string_name = $first . " " . $middle;
        $rand_no = 10;
        $userRepository = $this->getDoctrine()->getManager()->getRepository(User::class);
        while (true) {
            $username_parts = array_filter(explode(" ", strtolower($string_name))); //explode and lowercase name
            $username_parts = array_slice($username_parts, 0, 2); //return only first two arry part
            $part1 = (!empty($username_parts[0])) ? substr($username_parts[0], 0, rand(4, 6)) : ""; //cut fi rs t  name to 8 letters
            $part2 = (!empty($username_parts[1])) ? substr($username_parts[1], 0, rand(3, 5)) : ""; //cut se co n d name to 5 letters
            $username = $part1 . $part2; //str _shuffle to randomly shuffle all characters 
            if (!$userRepository->findOneBy(['username' => $username]))
                break;
        }
        return $username;
    }

    static function randomPassword()
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}
