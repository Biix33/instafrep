<?php

namespace App\Controller;

use App\Utils\ImageCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{
    /**
     * @Route("/image", name="image")
     * @param ImageCreator $imageCreator
     * @return Response
     */
    public function index(ImageCreator $imageCreator)
    {
        $png = $imageCreator->getImage();
        $response = new Response($png);
        $response->headers->set('Content-type', 'image/png');
        return $response;
    }
}
