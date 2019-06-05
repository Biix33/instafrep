<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Utils\ImageCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("user/combo", name="user_combo", methods={"PUT", "PATCH"})
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function combo(Request $request)
    {
        if ($request->isXmlHttpRequest()):
            $user = $this->getUser();
            $user->setComboUnlock(true);
            $this->getDoctrine()->getManager()->flush();
            return new Response(null, 204);
        endif;
        return new Response('Use only AJAX to reach this route', 405);
    }

    /**
     * @Route("/user/{id}", name="user_profile", requirements={"id"="\d+"})
     * @param int $id
     * @return Response
     */
    public function profile(int $id)
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
     * @Route("/user/me", name="user_update")
     * @param Request $request
     * @param ImageCreator $imageCreator
     * @return RedirectResponse|Response
     */
    public function update(Request $request, ImageCreator $imageCreator)
    {
        /** @var $user User */
        $user = $this->getUser();
        $user->setAvatar(null); // TODO: remove this line asap

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()):

            /** @var UploadedFile $file */
            $file = $user->getAvatar();
            if (!$file):
                $file = $imageCreator->getImage(60, 60);
            endif;
            $ext = $file->guessExtension();
            $basename = 'profile-picture-'.$user->getId();
            $filename = $basename . '.' . $ext;
            $file->move($this->getParameter('user_upload_folder'), $filename);
            $user->setAvatar($filename);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('user_profile', ['id' => $user->getId()]);
        endif;

        return $this->render('user/create.html.twig', [
            'user_form' => $form->createView(),
        ]);
    }
}
