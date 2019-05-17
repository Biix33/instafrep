<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{id}", name="user_profile")
     */
    public function profile($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        if (empty($user)):
            throw $this->createNotFoundException("User $id not found");
        endif;

        return $this->render('user/profile.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user/new", name="user_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);
        return $this->render('user/create.html.twig', [
            'user_form' => $form->createView(),
        ]);
    }
}
