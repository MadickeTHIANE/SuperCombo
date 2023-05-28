<?php

namespace App\Controller;

use App\Entity\Slide;
use App\Entity\Video;
use App\Entity\Article;
use App\Entity\BlogBillet;
use App\Repository\MediaRepository;
use App\Repository\SlideRepository;
use App\Repository\VideoRepository;
use App\Repository\ArticleRepository;
use App\Repository\BlogBilletRepository;
use App\Repository\BlogDiscussionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
 */
class IndexController extends AbstractController
{
    const BLOG_INDEX = 'index/blog.html.twig';

    //*ok
    /**
     * @Route("/", name="index")
     */
    public function index(VideoRepository $videoRepository, SlideRepository $slideRepository, TranslatorInterface $translator, Request $request)
    {
        $videos = $videoRepository->findBy([], ['id' => 'desc']);
        $slides = $slideRepository->findBy(["active" => 0]);
        $slideActive = $slideRepository->findBy(["active" => 1])[0];
        $locale = $request->getLocale();

        return $this->render('index/index.html.twig', [
            "videos" => $videos,
            "slideActive" => $slideActive,
            "slides" => $slides,
            "locale"=>$locale,
        ]);
    }
    
    /**
     * @Route("/search",name="search")
     */
    public function search(Request $request, ArticleRepository $articleRepository, BlogDiscussionRepository $blogDiscussionRepository)
    {
        $term = $request->query->get('search');

        if (!$term) {
            return $this->redirectToRoute('index');
        }
        
        $articles = $articleRepository->searchByTerm($term);
        $discussions = $blogDiscussionRepository->searchByTerm($term);

        return $this->render('index/search.html.twig',[
            'term'=>$term,
            'articles' => $articles,
            'discussions' => $discussions
        ]);
    }

    //*ok
    /**
     * @Route("/show/video/{videoId}",name="show_video")
     */
    public function showVideo(Request $request, VideoRepository $videoRepository, SlideRepository $slideRepository, $videoId)
    {
        $video = $videoRepository->find($videoId);
        $videos = [$video];

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
    public function articleIndex(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findBy([], ['id' => 'desc']);
        return $this->render('index/article.html.twig', [
            "articles" => $articles
        ]);
    }

    //*ok
    /**
     * @Route("/blog",name="blog_index")
     */
    public function blogIndex(BlogBilletRepository $blogBilletRepository)
    {
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
    public function billetDiscussionIndex(Request $request, BlogBilletRepository $blogBilletRepository, $billetId)
    {
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
    public function showArticle(Request $request, ArticleRepository $articleRepository, $articleId)
    {
        $article = $articleRepository->find($articleId);
        if (!$article) {
            return $this->redirect($this->generateUrl('article_index'));
        }
        $articles = [$article];
        return $this->render('index/article.html.twig', [
            "articles" => $articles
        ]);
    }

    /**
     * @Route("/show/image/{imageId}",name="show_image")
     */
    public function showImage(Request $request, MediaRepository $mediaRepository, $imageId)
    {
        $image = $mediaRepository->find($imageId);
        $images = [$image];
        return $this->render('index/mediaIndex.html.twig', [
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
