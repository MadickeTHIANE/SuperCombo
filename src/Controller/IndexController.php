<?php

namespace App\Controller;

use App\Entity\Video;
use App\Entity\Article;
use App\Form\ArticleType;
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
    //*ok
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $videoRepository = $entityManager->getRepository(Video::class);
        $videos = $videoRepository->findAll();

        return $this->render('index/index.html.twig', [
            "videos" => $videos
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
        $articles = $articleRepository->findAll();
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
        $blogBillets = $blogBilletRepository->findAll();
        if (!$blogBillets) {
            return $this->redirect($this->generateUrl('create_billet'));
        }

        return $this->render('index/blog.html.twig', [
            "blogBillets" => $blogBillets
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

        //Renvoie des discussions et de leur billet
        return $this->render('index/blog.html.twig', [
            "blogDiscussions" => $blogDiscussions,
            "blogBillet" => $blogBillet,
        ]);
    }


    //*ok
    /**
     * @Route("/article/create",name="create_article")
     */
    public function createArticle(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $article = new Article;
        $articleForm = $this->createForm(ArticleType::class, $article);
        $articleForm->handleRequest($request);
        if ($request->isMethod('post') && $articleForm->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('article_index'));
        }
        return $this->render('index/dataform.html.twig', [
            "dataForm" => $articleForm->createView(),
            "formName" => "Création d'un article"
        ]);
    }

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
     * @Route("article/edit/{articleId}",name="edit_article")
     */
    public function editArticle(Request $request, $articleId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $articleRepository = $entityManager->getRepository(Article::class);
        $article = $articleRepository->find($articleId);
        if (!$article) {
            return $this->redirect($this->generateUrl('article_index'));
        }
        $articleForm = $this->createForm(ArticleType::class, $article);
        $articleForm->handleRequest($request);
        if ($request->isMethod('post') && $articleForm->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('article_index'));
        }
        return $this->render('index/dataform.html.twig', [
            "dataForm" => $articleForm->createView(),
            "formName" => "Modification de l'article"
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
     * @Route("/article/delete/{articleId}",name="delete_article")
     */
    public function deleteArticle(Request $request, $articleId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $articleRepository = $entityManager->getRepository(Article::class);
        $article = $articleRepository->find($articleId);
        if (!$article) {
            return $this->redirect($this->generateUrl('article_index'));
        }
        $entityManager->remove($article);
        $entityManager->flush();
        return $this->redirect($this->generateUrl('article_index'));
    }

    //*ok
    /**
     * @Route("/blog/billet/delete/{billetId}",name="delete_billet")
     */
    public function deleteBillet(Request $request, $billetId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $blogBilletRepository = $entityManager->getRepository(BlogBillet::class);
        $billet = $blogBilletRepository->find($billetId);
        if (!$billet) {
            return $this->redirect($this->generateUrl('index'));
        }
        $billetDiscussions = $billet->getBlogDiscussions();
        foreach ($billetDiscussions as $billetDiscussion) {
            $entityManager->remove($billetDiscussion);
        }
        $entityManager->remove($billet);
        $entityManager->flush();
        return $this->redirect($this->generateUrl('blog_index'));
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
        $billetId = $discussion->getBillet()->getId();
        $entityManager->remove($discussion);
        $entityManager->flush();
        return $this->redirect($this->generateUrl('discussion_index', [
            "billetId" => $billetId
        ]));
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
        return $this->render('index/index.html.twig', [
            "videos" => $videos
        ]);
    }
}
