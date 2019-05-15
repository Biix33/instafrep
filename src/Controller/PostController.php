<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/post", name="post")
     */
    public function index()
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)->findBy([
            'public' => true,
        ]);
        return $this->render('post/list.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/post/{id}", name="post_single", requirements={"id"="[0-9]+"})
     * @param $id
     * @return RedirectResponse|Response
     */
    public function showOne($id)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);
        if ($post === null):
            return $this->redirectToRoute('post');
        endif;
        return $this->render('post/single.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("post/new", name="post_create")
     */
    public function create(Request $request)
    {
        $form = $this->createForm(PostType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()):
            // on crée un nouveau post
            $post = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
        endif;

        return $this->render('post/create.html.twig', [
            'post_form' => $form->createView(),
        ]);
    }
}
