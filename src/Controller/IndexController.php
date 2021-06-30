<?php

namespace App\Controller;

use App\Entity\BlogBillet;
use App\Form\BlogBilletType;
use App\Entity\BlogDiscussion;
use App\Form\BlogDiscussionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('index/index.html.twig', []);
    }

    /**
     * @Route("/blog",name="blog_index")
     */
    public function blogIndex()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $blogBilletRepository = $entityManager->getRepository(BlogBillet::class);

        //On récupère tous les billets
        $blogBillets = $blogBilletRepository->findAll();
        if (!$blogBillets) {
            return $this->redirect($this->generateUrl('create_billet'));
        }

        return $this->render('index/blog.html.twig', [
            "blogBillets" => $blogBillets
        ]);
    }

    /**
     * @Route("/blog/billet/{billetId}",name="discussion_index")
     */
    public function billetDiscussionIndex(Request $request, $billetId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $blogBilletRepository = $entityManager->getRepository(BlogBillet::class);
        //On récupère le billet correspondant à l'id
        $blogBillet = $blogBilletRepository->find($billetId);

        if (!$blogBillet) {
            return $this->redirect($this->generateUrl('blog_index'));
        }
        $blogDiscussions = $blogBillet->getBlogDiscussions();

        return $this->render('index/blog.html.twig', [
            "blogDiscussions" => $blogDiscussions,
            "blogBillet" => $blogBillet,
        ]);
    }

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
            $entityManager->persist($billet);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('blog_index'));
        }
        return $this->render('index/dataform.html.twig', [
            "dataForm" => $billetForm->createView(),
            "formName" => "Création d'un nouveau sujet",
        ]);
    }

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
            $entityManager->persist($discussion);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('blog_index'));
        }
        return $this->render('index/dataform.html.twig', [
            "dataForm" => $discussionForm->createView(),
            "formName" => "Création d'un nouveau commentaire",
            // "billetId"=>$billetId
        ]);
    }
}
