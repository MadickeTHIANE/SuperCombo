<?php

namespace App\Controller;

use App\Entity\BlogBillet;
use App\Entity\BlogDiscussion;
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
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    /**
     * @Route("/blog",name="blog_index")
     */
    public function blogIndex()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $blogBilletRepository = $entityManager->getRepository(BlogBillet::class);
        $blogBillets = $blogBilletRepository->findAll();
        return $this->render('index/blog.html.twig', [
            "blogBillets" => $blogBillets
        ]);
    }

    /**
     * @Route("/blog/billet/{billetId}",name="discussion_display")
     */
    public function billetDiscussionDisplay(Request $request, $billetId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $blogBilletRepository = $entityManager->getRepository(BlogBillet::class);
        $blogBillet = $blogBilletRepository->find($billetId);
        $blogDiscussions = $blogBillet->getBlogDiscussions();

        return $this->render('index/blog.html.twig', [
            "blogDiscussions" => $blogDiscussions
        ]);
    }
}
