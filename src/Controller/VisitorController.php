<?php

namespace App\Controller;

use App\Entity\BlogBillet;
use App\Form\BlogBilletType;
use App\Entity\BlogDiscussion;
use App\Form\BlogDiscussionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 *@Route("/user")
 *@Security("is_granted('ROLE_USER')") 
 */
class VisitorController extends AbstractController
{
    //*ok
    /**
     * @Route("/blog/billet/create",name="create_billet")
     */
    public function createBillet(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $billet = new BlogBillet;
        $billetForm = $this->createForm(BlogBilletType::class, $billet);
        $billetForm->handleRequest($request);
        if ($request->isMethod('post') && $billetForm->isValid()) {
            $billet->setAuteur($this->getUser()->getUsername());
            $entityManager->persist($billet);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('blog_index'));
        }
        return $this->render('index/dataform.html.twig', [
            "dataForm" => $billetForm->createView(),
            "formName" => "Création d'un nouveau sujet",
        ]);
    }

    //*ok
    /**
     * @Route("/blog/discussion/create/{billetId}",name="create_discussion")
     */
    public function createDiscussion(Request $request, $billetId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $blogBilletRepository = $entityManager->getRepository(BlogBillet::class);

        $billet = $blogBilletRepository->find($billetId);
        $discussion = new BlogDiscussion($billet);
        $discussionForm = $this->createForm(BlogDiscussionType::class, $discussion);
        $discussionForm->handleRequest($request);
        if ($request->isMethod('post') && $discussionForm->isValid()) {
            $discussion->setAuteur($this->getUser()->getUsername());
            $discussion->setBillet($billet);
            $entityManager->persist($discussion);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('discussion_index', [
                "billetId" => $billetId
            ]));
        }
        return $this->render('index/dataform.html.twig', [
            "dataForm" => $discussionForm->createView(),
            "formName" => "Création d'un nouveau commentaire",
        ]);
    }


    //*ok
    /**
     * @Route("/blog/billet/edit/{billetId}",name="edit_billet")
     */
    public function editBillet(Request $request, $billetId)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $billetRepository = $entityManager->getRepository(BlogBillet::class);
        $billet = $billetRepository->find($billetId);
        if (!$billet) {
            return $this->redirect($this->generateUrl('blog_index'));
        }

        $user = $this->getUser();
        if ($user->getUsername() != $billet->getAuteur()) {
            return $this->redirect($this->generateUrl('blog_index'));
        }

        $billetForm = $this->createForm(BlogBilletType::class, $billet);
        $billetForm->handleRequest($request);
        if ($request->isMethod('post') && $billetForm->isValid()) {
            $entityManager->persist($billet);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('blog_index'));
        }
        return $this->render('index/dataform.html.twig', [
            "dataForm" => $billetForm->createView(),
            "formName" => "Modification du billet"
        ]);
    }

    //*ok
    /**
     * @Route("/blog/discussion/edit/{discussionId}",name="edit_discussion")
     */
    public function editDiscussion(Request $request, $discussionId)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $discussionRepository = $entityManager->getRepository(BlogDiscussion::class);
        $discussion = $discussionRepository->find($discussionId);
        if (!$discussion) {
            return $this->redirect($this->generateUrl('blog_index'));
        }

        $user = $this->getUser();
        if ($user->getUsername() != $discussion->getAuteur()) {
            return $this->redirect($this->generateUrl('blog_index'));
        }

        $billetId = $discussion->getBillet()->getId();
        $discussionForm = $this->createForm(BlogDiscussionType::class, $discussion);
        $discussionForm->handleRequest($request);
        if ($request->isMethod('post') && $discussionForm->isValid()) {
            $entityManager->persist($discussion);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('discussion_index', [
                "billetId" => $billetId
            ]));
        }
        return $this->render('index/dataform.html.twig', [
            "dataForm" => $discussionForm->createView(),
            "formName" => "Modification du commentaire"
        ]);
    }


    //*ok
    /**
     * @Route("/blog/discussion/delete/{discussionId}",name="delete_discussion")
     */
    public function deleteDiscussion(Request $request, $discussionId)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $discussionRepository = $entityManager->getRepository(BlogDiscussion::class);
        $discussion = $discussionRepository->find($discussionId);
        if (!$discussion) {
            return $this->redirect($this->generateUrl('index'));
        }

        $user = $this->getUser();
        if ($user->getUsername() != $discussion->getAuteur()) {
            return $this->redirect($this->generateUrl('blog_index'));
        }

        $billetId = $discussion->getBillet()->getId();
        $entityManager->remove($discussion);
        $entityManager->flush();
        return $this->redirect($this->generateUrl('discussion_index', [
            "billetId" => $billetId
        ]));
    }
}
