<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    public function homePage(Request $request)
    {
        $name = $request->get('name');
        if (!empty($name)):
            return new Response('Hello ' . $name);
        endif;
        return new Response('Hello world');
    }

    /**
     * @Route("rand-digit/", name="ajax_response")
     * @param Request $request
     * @return Response
     */
    public function randomDigit(Request $request)
    {
        $rand = rand(0, 100);
        if ($request->isXmlHttpRequest()) :
            sleep(3);
            return new Response($rand);
        endif;
        return $this->render('ajax.html.twig', ['rand' => $rand]);
    }
}