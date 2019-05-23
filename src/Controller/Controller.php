<?php


namespace App\Controller;


use Symfony\Component\DependencyInjection\Loader\Configurator\AbstractConfigurator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller extends AbstractConfigurator
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