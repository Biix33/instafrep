<?php

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AgeController extends AbstractController
{
    public function guessYear($age): ?int
    {
        $currentYear = new DateTime('now');
        return (is_numeric($age)) ? $currentYear->format('Y') - $age : null;
    }

    /**
     * @Route("/year", name="age")
     * @param Request $request
     */
    public function getBirthYear(Request $request)
    {
        $age = $request->get('age');
        $birthYear = $this->guessYear($age);
        return $this->render('age/public.posts.html.twig', ['year' => $birthYear]);
    }
}
