<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;

class Controller
{
    public function homePage()
    {
        return new Response('Hello');
    }
}