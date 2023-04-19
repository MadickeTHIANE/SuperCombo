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

class SecurityController extends AbstractController
{
    /**
     * @Security("is_granted('ROLE_SUPERADMIN')")
     * @Route("/admin_register",name="admin_register")
     */
    public function adminRegister(Request $request, UserPasswordHasherInterface $passwordHasher)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $userForm = $this->createFormBuilder()
            ->add('username', TextType::class, [
                'label' => "Nom de l'utilisateur",
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmation du mot de passe'],
            ])
            ->add('matricle', TextType::class, [
                'label' => "Matricule de l'utilisateur",
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Role : USER' => 'ROLE_USER',
                    'Role : ADMIN' => 'ROLE_ADMIN',
                    'Role : SUPERADMIN' => 'ROLE_SUPERADMIN',
                ],
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('register', SubmitType::class, [
                'label' => "Inscrire",
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
            "formName" => "Inscription (Admin)"
        ]);
    }

    /**
     *@Route("/register",name="user_register") 
     */
    public function userRegister(Request $request, UserPasswordHasherInterface $passwordHasher)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userForm = $this->createFormBuilder()
            ->add('username', TextType::class, [
                'label' => 'Pseudo'
            ])
            ->add('mail', EmailType::class, [
                'label' => "email"
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => "Votre mot de passe"],
                'second_options' => ['label' => "Confirmation du mot de passe"],
            ])
            ->add('register', SubmitType::class, [
                'label' => "S'inscrire",
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
            "formName" => "Inscription"
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
