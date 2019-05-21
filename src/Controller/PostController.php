<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/posts", name="post", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $currentPage = $request->query->get('p');
        $limit = $request->query->get('l');
        $page = (!isset($currentPage) || $currentPage <= 0) ? 1 : $currentPage;
        $skip = (($page - 1) * 5);
        $nbPosts = $this->getDoctrine()->getRepository(Post::class)->count(['public' => 'true']);
        $nbPages = ceil($nbPosts / 5);
        $posts = $this
            ->getDoctrine()
            ->getRepository(Post::class)
            ->findHomePage($skip, 5);

        return $this->render('post/list.html.twig', [
            'posts' => $posts,
            'page' => $page,
            'nb_page' => $nbPages,
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
        $form = $this->createForm(CommentType::class);
        if ($post === null):
            return $this->redirectToRoute('post');
        endif;
        return $this->render('post/single.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("post/new", name="post_create")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function create(Request $request)
    {
        $form = $this->createForm(PostType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()):
            // on crée un nouveau post
            $user = $this->getDoctrine()->getRepository(User::class)->find(rand(11, 19));
            $post = $form->getData();
            $post->setUserId($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('post');
        endif;

        return $this->render('post/create.html.twig', [
            'post_form' => $form->createView(),
        ]);
    }

    /**
     * @Route("post/delete/{id}", name="post_delete")
     * @param $id
     * @return RedirectResponse
     */
    public function delete($id)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);

        if (!$post):
            throw $this->createNotFoundException("No post found for id $id");
        endif;

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($post);
        $manager->flush();
        return $this->redirectToRoute('post');
    }

    /**
     * @Route("post/update/{id}", name="post_update")
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function update($id, Request $request)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()):
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('post');
        endif;

        return $this->render('post/create.html.twig', [
            'post_form' => $form->createView(),
        ]);
    }
}
