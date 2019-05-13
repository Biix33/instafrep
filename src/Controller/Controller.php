<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller
{
    public function homePage(Request $request)
    {
        $name = $request->get('name');
        if (!empty($name)):
            return new Response('Hello '.$name);
        endif;
        return new Response('Hello world');
    }
}