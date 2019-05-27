<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{id}", name="user_profile")
     * @param $id
     * @return Response
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
     * @return Response
     */
    public function create(Request $request)
    {
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);
        return $this->render('user/create.html.twig', [
            'user_form' => $form->createView(),
        ]);
    }

    /**
     * @Route("user/update/{id}", name="user_update")
     * @param $id
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function update($id, Request $request)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()):
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('user_profile', ['id' => $id]);
        endif;

        return $this->render('user/create.html.twig', [
            'user_form' => $form->createView(),
        ]);
    }
}
