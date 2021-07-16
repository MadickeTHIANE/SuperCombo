<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\VideoType;
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
     * @Route("/", name="admin_index")
     */
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $videoRepository = $entityManager->getRepository(Video::class);
        $videos = $videoRepository->findAll();

        return $this->render('admin/index.html.twig', [
            "videos" => $videos
        ]);
    }

    /**
     * @Route("/add/video", name = "add_video")
     */
    public function addVideo(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $video = new Video;
        $videoForm = $this->createForm(VideoType::class, $video);
        $videoForm->handleRequest($request);

        if ($request->isMethod('post') && $videoForm->isValid()) {
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
            return $this->redirect($this->generateUrl('index'));
        }
        return $this->render('index/dataform.html.twig', [
            "formName" => "Ajouter une vidéo",
            "dataForm" => $videoForm->createView()
        ]);
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
        return $this->redirect($this->generateUrl('index'));
    }
}
