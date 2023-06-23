<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Visitor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route(
 *     "/{_locale}",
 *     defaults={
 *         "_locale": "fr",
 *     },
 *     requirements={
 *         "_locale": "fr|en",
 *     }
 * )
 */class SecurityController extends AbstractController
{
    /**
     * @Security("is_granted('ROLE_SUPERADMIN')")
     * @Route("/admin_register",name="admin_register")
     */
    public function adminRegister(Request $request, UserPasswordHasherInterface $passwordHasher, TranslatorInterface $translator)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $userForm = $this->createFormBuilder()
            ->add('username', TextType::class, [
                'label' => $translator->trans('admin_register.label.username'),
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => $translator->trans('admin_register.label.password')],
                'second_options' => ['label' => $translator->trans('admin_register.label.password_confirmation')],
            ])
            ->add('matricle', TextType::class, [
                'label' => $translator->trans('admin_register.label.matricule'),
            ])
            ->add('roles', ChoiceType::class, [
                'label' => $translator->trans('admin_register.label.role'),
                'choices' => [
                    'Role : USER' => 'ROLE_USER',
                    'Role : ADMIN' => 'ROLE_ADMIN',
                    'Role : SUPERADMIN' => 'ROLE_SUPERADMIN',
                ],
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('register', SubmitType::class, [
                'label' => $translator->trans('admin_register.label.register'),
                'attr' => [
                    'class' => 'btn btn-success',
                    'style' => 'margin-top : 5px',
                ]
            ])
            ->getForm();

        $userForm->handleRequest($request);
        if ($request->isMethod('post') && $userForm->isValid()) {
            $datas = $userForm->getData();
            $user = new Admin;
            $user->setUsername($datas['username']);
            $user->setMatricle($datas['matricle']);
            $user->setPassword($passwordHasher->hashPassword($user, $datas['password']));
            $user->setRoles($datas['roles']);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('app_login'));
        }
        return $this->render('index/dataform.html.twig', [
            "dataForm" => $userForm->createView(),
            "formName" => $translator->trans('header.signIn')." (Admin)"
        ]);
    }

    /**
     *@Route("/register",name="user_register") 
     */
    public function userRegister(Request $request, UserPasswordHasherInterface $passwordHasher, TranslatorInterface $translator)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userForm = $this->createFormBuilder()
            ->add('username', TextType::class, [
                'label' => $translator->trans('register.label.username')
            ])
            ->add('mail', EmailType::class, [
                'label' => $translator->trans('register.label.mail')
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => $translator->trans('register.label.password')],
                'second_options' => ['label' => $translator->trans('register.label.password_confirmation')],
            ])
            ->add('register', SubmitType::class, [
                'label' => $translator->trans('register.confirm'),
                'attr' => [
                    'style' => "margin-top:5px",
                    'class' => "btn btn-success"
                ]
            ])
            ->getForm();

        $userForm->handleRequest($request);
        if ($request->isMethod('post') && $userForm->isValid()) {
            $datas = $userForm->getData();
            $user = new Visitor;
            $user->setUsername($datas['username']);
            $user->setMail($datas['mail']);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($passwordHasher->hashPassword($user, $datas['password']));
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('app_login'));
        }
        return $this->render('index/dataform.html.twig', [
            "dataForm" => $userForm->createView(),
            "formName" => $translator->trans('header.signIn')
        ]);
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
