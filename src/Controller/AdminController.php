<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Slide;
use App\Entity\Video;
use App\Entity\Article;
use App\Form\ImageType;
use App\Form\SlideType;
use App\Form\VideoType;
use App\Form\ArticleType;
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
 *@Route("/admin")
 *@Security("is_granted('ROLE_ADMIN')") 
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/video", name="admin_video_index")
     */
    public function videoIndex(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $videoRepository = $entityManager->getRepository(Video::class);
        $videos = $videoRepository->findBy([], ['id' => 'desc']);

        return $this->render('admin/videoIndex.html.twig', [
            "videos" => $videos
        ]);
    }

    /**
     * @Route("/image", name="admin_image_index")
     */
    public function imageIndex(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $imageRepository = $entityManager->getRepository(Image::class);
        $images = $imageRepository->findBy([], ['id' => 'desc']);

        return $this->render('admin/imageIndex.html.twig', [
            "images" => $images
        ]);
    }

    /**
     * @Route("/slide", name="admin_slide_index")
     */
    public function slideIndex(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $slideRepository = $entityManager->getRepository(Slide::class);
        $slides = $slideRepository->findAll();

        //todo Compléter la page admin/slideIndex.html.twig
        return $this->render('admin/slideIndex.html.twig', [
            "slides" => $slides
        ]);
    }

    /**
     * @Route("/blog", name="admin_blog_index")
     */
    public function blogIndex(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $billetsRepository = $entityManager->getRepository(BlogBillet::class);
        $billets = $billetsRepository->findBy([], ['id' => 'desc']);

        return $this->render('admin/blog.html.twig', [
            "blogBillets" => $billets
        ]);
    }

    /**
     * @Route("/blog/billet/display/{billetId}",name="admin_discussion_index")
     */
    public function billetDiscussionIndex(Request $request, $billetId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $blogBilletRepository = $entityManager->getRepository(BlogBillet::class);
        //On récupère le billet correspondant à l'id
        $blogBillet = $blogBilletRepository->find($billetId);

        if (!$blogBillet) {
            return $this->redirect($this->generateUrl('admin_blog_index'));
        }
        $blogDiscussions = $blogBillet->getBlogDiscussions();

        //Renvoie des discussions et de leur billet
        return $this->render('admin/blog.html.twig', [
            "blogDiscussions" => $blogDiscussions,
            "blogBillet" => $blogBillet,
        ]);
    }

    /**
     *@Route("/article",name="admin_article_index") 
     */
    public function articleIndex()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $articleRepository = $entityManager->getRepository(Article::class);
        $articles = $articleRepository->findBy([], ['id' => 'desc']);
        if (!$articles) {
            return $this->redirect($this->generateUrl('admin_create_article'));
        }
        return $this->render('admin/article.html.twig', [
            "articles" => $articles
        ]);
    }

    /**
     * @Route("/add/video", name = "add_video")
     */
    public function addVideo(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $video = new Video;
        $videoForm = $this->createForm(VideoType::class, $video);
        $videoForm->handleRequest($request);

        if ($request->isMethod('post') && $videoForm->isValid()) {
            $video->setUser($user);
            $entityManager->persist($video);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('index'));
        }
        return $this->render('index/dataform.html.twig', [
            "formName" => "Ajouter une vidéo",
            "dataForm" => $videoForm->createView()
        ]);
    }

    /**
     * @Route("/add/image", name = "add_image")
     */
    public function addImage(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $image = new Image;
        $imageForm = $this->createForm(ImageType::class, $image);
        $imageForm->handleRequest($request);

        if ($request->isMethod('post') && $imageForm->isValid()) {
            $image->setUser($user);
            $entityManager->persist($image);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('admin_image_index'));
        }
        return $this->render('index/dataform.html.twig', [
            "formName" => "Ajouter une image",
            "dataForm" => $imageForm->createView()
        ]);
    }

    /**
     * @Route("/add/slide", name = "add_slide")
     */
    public function addSlide(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $slide = new Slide;
        $slideForm = $this->createForm(SlideType::class, $slide);
        $slideForm->handleRequest($request);

        if ($request->isMethod('post') && $slideForm->isValid()) {
            $entityManager->persist($slide);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('admin_slide_index'));
        }
        return $this->render('index/dataform.html.twig', [
            "formName" => "Ajouter une slide",
            "dataForm" => $slideForm->createView()
        ]);
    }


    /**
     * @Route("/show/image/{imageId}",name="admin_show_image")
     */
    public function showImage(Request $request, $imageId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $imageRepository = $entityManager->getRepository(Image::class);
        $image = $imageRepository->find($imageId);
        $images = [$image];
        return $this->render('admin/imageIndex.html.twig', [
            "images" => $images
        ]);
    }

    /**
     * @Route("/blog/billet/create",name="admin_create_billet")
     */
    public function createBillet(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $billet = new BlogBillet;
        $user = $this->getUser();
        $billetForm = $this->createForm(BlogBilletType::class, $billet);
        $billetForm->handleRequest($request);
        if ($request->isMethod('post') && $billetForm->isValid()) {
            $billet->setAuteur($this->getUser()->getUsername());
            $billet->setUser($user);
            $entityManager->persist($billet);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('admin_blog_index'));
        }
        return $this->render('index/dataform.html.twig', [
            "dataForm" => $billetForm->createView(),
            "formName" => "Création d'un nouveau sujet",
        ]);
    }

    /**
     * @Route("/blog/discussion/create/{billetId}",name="admin_create_discussion")
     */
    public function createDiscussion(Request $request, $billetId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $blogBilletRepository = $entityManager->getRepository(BlogBillet::class);

        $billet = $blogBilletRepository->find($billetId);
        $user = $this->getUser();
        $discussion = new BlogDiscussion($billet);
        $discussionForm = $this->createForm(BlogDiscussionType::class, $discussion);
        $discussionForm->handleRequest($request);
        if ($request->isMethod('post') && $discussionForm->isValid()) {
            $discussion->setAuteur($this->getUser()->getUsername());
            $discussion->setUser($user);
            $discussion->setBillet($billet);
            $entityManager->persist($discussion);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('admin_discussion_index', [
                "billetId" => $billetId
            ]));
        }
        return $this->render('index/dataform.html.twig', [
            "dataForm" => $discussionForm->createView(),
            "formName" => "Création d'un nouveau commentaire",
        ]);
    }

    /**
     * @Route("/article/create",name="admin_create_article")
     */
    public function createArticle(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $article = new Article;
        $user = $this->getUser();
        $articleForm = $this->createForm(ArticleType::class, $article);
        $articleForm->handleRequest($request);
        if ($request->isMethod('post') && $articleForm->isValid()) {
            $article->setUser($user);
            $entityManager->persist($article);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('admin_article_index'));
        }
        return $this->render('index/dataform.html.twig', [
            "dataForm" => $articleForm->createView(),
            "formName" => "Création d'un article"
        ]);
    }

    /**
     * @Route("/show/article/{articleId}",name="admin_show_article")
     */
    public function showArticle(Request $request, $articleId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $articleRepository = $entityManager->getRepository(Article::class);
        $article = $articleRepository->find($articleId);
        if (!$article) {
            return $this->redirect($this->generateUrl('admin_article_index'));
        }
        $articles = [$article];
        return $this->render('admin/article.html.twig', [
            "articles" => $articles
        ]);
    }

    /**
     * @Route("article/edit/{articleId}",name="admin_edit_article")
     */
    public function editArticle(Request $request, $articleId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $articleRepository = $entityManager->getRepository(Article::class);
        $article = $articleRepository->find($articleId);
        if (!$article) {
            return $this->redirect($this->generateUrl('admin_article_index'));
        }
        $articleForm = $this->createForm(ArticleType::class, $article);
        $articleForm->handleRequest($request);
        if ($request->isMethod('post') && $articleForm->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('admin_article_index'));
        }
        return $this->render('index/dataform.html.twig', [
            "dataForm" => $articleForm->createView(),
            "formName" => "Modification de l'article"
        ]);
    }

    /**
     * @Route("/edit/video/{videoId}", name = "edit_video")
     */
    public function editVideo(Request $request, $videoId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $videoRepository = $entityManager->getRepository(Video::class);
        $video = $videoRepository->find($videoId);

        $videoForm = $this->createForm(VideoType::class, $video);
        $videoForm->handleRequest($request);

        if ($request->isMethod('post') && $videoForm->isValid()) {
            $entityManager->persist($video);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('admin_video_index'));
        }
        return $this->render('index/dataform.html.twig', [
            "formName" => "Modifier une vidéo",
            "dataForm" => $videoForm->createView()
        ]);
    }

    /**
     * @Route("/edit/image/{imageId}", name = "edit_image")
     */
    public function editImage(Request $request, $imageId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $imageRepository = $entityManager->getRepository(Image::class);
        $image = $imageRepository->find($imageId);

        $imageForm = $this->createForm(ImageType::class, $image);
        $imageForm->handleRequest($request);

        if ($request->isMethod('post') && $imageForm->isValid()) {
            $entityManager->persist($image);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('admin_image_index'));
        }
        return $this->render('index/dataform.html.twig', [
            "formName" => "Modifier une image",
            "dataForm" => $imageForm->createView()
        ]);
    }

    /**
     * @Route("/edit/slide/{slideId}", name = "edit_slide")
     */
    public function editSlide(Request $request, $slideId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $slideRepository = $entityManager->getRepository(Slide::class);
        $slide = $slideRepository->find($slideId);

        $slideForm = $this->createForm(SlideType::class, $slide);
        $slideForm->handleRequest($request);

        if ($request->isMethod('post') && $slideForm->isValid()) {
            $entityManager->persist($slide);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('admin_slide_index'));
        }
        return $this->render('index/dataform.html.twig', [
            "formName" => "Modifier une slide",
            "dataForm" => $slideForm->createView()
        ]);
    }

    /**
     * @Route("/blog/billet/edit/{billetId}",name="admin_edit_billet")
     */
    public function editBillet(Request $request, $billetId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $billetRepository = $entityManager->getRepository(BlogBillet::class);
        $billet = $billetRepository->find($billetId);
        if (!$billet) {
            return $this->redirect($this->generateUrl('index'));
        }
        $billetForm = $this->createForm(BlogBilletType::class, $billet);
        $billetForm->handleRequest($request);
        if ($request->isMethod('post') && $billetForm->isValid()) {
            $entityManager->persist($billet);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('admin_blog_index'));
        }
        return $this->render('index/dataform.html.twig', [
            "dataForm" => $billetForm->createView(),
            "formName" => "Modification du billet"
        ]);
    }

    /**
     * @Route("/blog/discussion/edit/{discussionId}",name="admin_edit_discussion")
     */
    public function editDiscussion(Request $request, $discussionId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $discussionRepository = $entityManager->getRepository(BlogDiscussion::class);
        $discussion = $discussionRepository->find($discussionId);
        if (!$discussion) {
            return $this->redirect($this->generateUrl('admin_blog_index'));
        }
        $billetId = $discussion->getBillet()->getId();
        $discussionForm = $this->createForm(BlogDiscussionType::class, $discussion);
        $discussionForm->handleRequest($request);
        if ($request->isMethod('post') && $discussionForm->isValid()) {
            $entityManager->persist($discussion);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('admin_discussion_index', [
                "billetId" => $billetId
            ]));
        }
        return $this->render('index/dataform.html.twig', [
            "dataForm" => $discussionForm->createView(),
            "formName" => "Modification du commentaire"
        ]);
    }

    /**
     * @Route("/article/delete/{articleId}",name="admin_delete_article")
     */
    public function deleteArticle(Request $request, $articleId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $articleRepository = $entityManager->getRepository(Article::class);
        $article = $articleRepository->find($articleId);
        if (!$article) {
            return $this->redirect($this->generateUrl('admin_article_index'));
        }

        $images = $article->getImages();
        if ($images != null) {
            foreach ($images as $image) {
                $entityManager->remove($image);
            }
        }

        $entityManager->remove($article);
        $entityManager->flush();
        return $this->redirect($this->generateUrl('admin_article_index'));
    }

    /**
     * @Route("/delete/video/{videoId}", name="delete_video")
     */
    public function deleteVideo(Request $request, $videoId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $videoRepository = $entityManager->getRepository(Video::class);
        $video = $videoRepository->find($videoId);
        if (!$video) {
            return $this->redirect($this->generateUrl('index'));
        }
        $entityManager->remove($video);
        $entityManager->flush();
        return $this->redirect($this->generateUrl('admin_video_index'));
    }

    /**
     * @Route("/delete/image/{imageId}", name="delete_image")
     */
    public function deleteImage(Request $request, $imageId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $imageRepository = $entityManager->getRepository(Image::class);
        $image = $imageRepository->find($imageId);
        if (!$image) {
            return $this->redirect($this->generateUrl('index'));
        }
        $entityManager->remove($image);
        $entityManager->flush();
        return $this->redirect($this->generateUrl('admin_image_index'));
    }

    /**
     * @Route("/delete/slide/{slideId}", name="delete_slide")
     */
    public function deleteSlide(Request $request, $slideId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $slideRepository = $entityManager->getRepository(Slide::class);
        $slide = $slideRepository->find($slideId);
        if (!$slide) {
            return $this->redirect($this->generateUrl('index'));
        }
        $entityManager->remove($slide);
        $entityManager->flush();
        return $this->redirect($this->generateUrl('admin_slide_index'));
    }

    /**
     * @Route("/blog/billet/delete/{billetId}",name="admin_delete_billet")
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
        return $this->redirect($this->generateUrl('admin_blog_index'));
    }

    /**
     * @Route("/blog/discussion/delete/{discussionId}",name="admin_delete_discussion")
     */
    public function deleteDiscussion(Request $request, $discussionId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $discussionRepository = $entityManager->getRepository(BlogDiscussion::class);
        $discussion = $discussionRepository->find($discussionId);
        if (!$discussion) {
            return $this->redirect($this->generateUrl('admin_blog_index'));
        }
        $billetId = $discussion->getBillet()->getId();
        $entityManager->remove($discussion);
        $entityManager->flush();
        return $this->redirect($this->generateUrl('admin_discussion_index', [
            "billetId" => $billetId
        ]));
    }
}
