<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Slide;
use App\Entity\Video;
use App\Entity\Article;
use App\Entity\BlogBillet;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    const BLOG_INDEX = 'index/blog.html.twig';

    //*ok
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();


        $videoRepository = $entityManager->getRepository(Video::class);
        $videos = $videoRepository->findBy([], ['id' => 'desc']);
        $slideRepository = $entityManager->getRepository(Slide::class);
        $slides = $slideRepository->findBy(["active" => 0]);
        $slideActive = $slideRepository->findBy(["active" => 1])[0];

        return $this->render('index/index.html.twig', [
            "videos" => $videos,
            "slideActive" => $slideActive,
            "slides" => $slides,
        ]);
    }

    //*ok
    /**
     *@Route("/article",name="article_index") 
     */
    public function articleIndex()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $articleRepository = $entityManager->getRepository(Article::class);
        $articles = $articleRepository->findBy([], ['id' => 'desc']);
        if (!$articles) {
            return $this->redirect($this->generateUrl('create_article'));
        }
        return $this->render('index/article.html.twig', [
            "articles" => $articles
        ]);
    }

    //*ok
    /**
     * @Route("/blog",name="blog_index")
     */
    public function blogIndex()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $blogBilletRepository = $entityManager->getRepository(BlogBillet::class);

        //On récupère tous les billets
        $blogBillets = $blogBilletRepository->findBy([], ['id' => 'desc']);
        if (!$blogBillets) {
            return $this->redirect($this->generateUrl('create_billet'));
        }

        $user = $this->getUser();
        if ($user == null) {
            return $this->render(self::BLOG_INDEX, [
                "blogBillets" => $blogBillets,
                "userName" => null
            ]);
        }

        return $this->render(self::BLOG_INDEX, [
            "blogBillets" => $blogBillets,
            "userName" => $user->getUsername()
        ]);
    }

    //*ok
    /**
     * @Route("/blog/billet/display/{billetId}",name="discussion_index")
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

        $user = $this->getUser();
        if ($user == null) {
            return $this->render(self::BLOG_INDEX, [
                "blogDiscussions" => $blogDiscussions,
                "blogBillet" => $blogBillet,
                "userName" => null
            ]);
        }

        //Renvoie des discussions et de leur billet
        return $this->render(self::BLOG_INDEX, [
            "blogDiscussions" => $blogDiscussions,
            "blogBillet" => $blogBillet,
            "userName" => $user->getUsername()
        ]);
    }

    //*ok
    /**
     * @Route("/show/article/{articleId}",name="show_article")
     */
    public function showArticle(Request $request, $articleId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $articleRepository = $entityManager->getRepository(Article::class);
        $article = $articleRepository->find($articleId);
        if (!$article) {
            return $this->redirect($this->generateUrl('article_index'));
        }
        $articles = [$article];
        return $this->render('index/article.html.twig', [
            "articles" => $articles
        ]);
    }

    //*ok
    /**
     * @Route("/show/video/{videoId}",name="show_video")
     */
    public function showVideo(Request $request, $videoId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $videoRepository = $entityManager->getRepository(Video::class);
        $video = $videoRepository->find($videoId);
        $videos = [$video];
        $slideRepository = $entityManager->getRepository(Slide::class);
        $slides = $slideRepository->findBy([], ['id' => 'desc']);
        $slideActive = $slideRepository->findBy(["active" => true]);
        return $this->render('index/index.html.twig', [
            "videos" => $videos,
            "slideActive" => $slideActive,
            "slides" => $slides,
        ]);
    }

    /**
     * @Route("/show/image/{imageId}",name="show_image")
     */
    public function showImage(Request $request, $imageId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $imageRepository = $entityManager->getRepository(Image::class);
        $image = $imageRepository->find($imageId);
        $images = [$image];
        return $this->render('index/imageIndex.html.twig', [
            "images" => $images
        ]);
    }

    /**
     * @Route("/mentions", name="mentions_legales")
     */
    public function showMention(Request $request)
    {
        return $this->render('index/mentionLegale.html.twig', []);
    }

    /**
     * @Route("/politique", name="politique_confidentialite")
     */
    public function showPolitique(Request $request)
    {
        return $this->render('index/politiqueConfidentialite.html.twig', []);
    }
}
