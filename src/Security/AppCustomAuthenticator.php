<?php

namespace App\Security;

use App\Entity\User;
use App\Entity\UserGroup;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;


class AppCustomAuthenticator extends AbstractLoginFormAuthenticator  
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private UrlGeneratorInterface $urlGenerator;
    private $em;
    private $entityManager;
    private $csrfTokenManager;
    private $passwordEncoder;
    private $user;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request): bool
    {
        // after  copied here, modify $request->getRequestUri();
        //return $request->isMethod('POST') && $this->getLoginUrl($request) === $request->getRequestUri();
   
      return self::LOGIN_ROUTE === $request->attributes->get('_route') && $request->isMethod('POST');
        }
    


    public function authenticate(Request $request): PassportInterface
    {
        $username = $request->request->get('username', '');

          $credentials = [
            'username' => $request->request->get('username'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];

        $request->getSession()->set(Security::LAST_USERNAME, $username);

        return new Passport(
            new UserBadge($username),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->get('_csrf_token')),
            ]
        );
    }


    public function getCredentials(Request $request)
    {
        $credentials = [
            'username' => $request->request->get('username'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set( Security::LAST_USERNAME, $credentials['username']
        );

        return $credentials;
    }


    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $credentials['username']]);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Invalid login');
        }
        $this->user = $user;

        return $user;
    }
    
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {

        $user = $token->getUser();

        // if ($user->getLastLogin() == null) {
        //     return new RedirectResponse($this->urlGenerator->generate('change_password'));
        // }

       // $this->user->setLastLogin(new \DateTime());
        $user->setLastLogin(new \DateTime('now'));
        $user->setIsOnline(true);
        $this->entityManager->flush();

        $permissions = [];
        foreach ($user->getRoles() as $role) {
            $permissions[] = $role;
        }
        // dd($user->getRoles());
       
        $groups = $user->getUserGroup();
        foreach ($groups as $key => $value) {

            $permission = $value->getPermissions();

            foreach ($permission as $key => $value1) {
                $permissions[] = $value1->getCode();
            }
        }
       // dd(  $groups );
        $request->getSession()->set("PERMISSION", $permissions);

        //  if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
 
        

            return new RedirectResponse($this->urlGenerator->generate('home'));
      //  }
            // throw new \Exception('TODO: provide a valid redirect inside ' . __FILE__
    
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
