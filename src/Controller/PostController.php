<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/", name="post", methods={"GET"})
     * @param Request $request
     * @param int $take
     * @return Response
     */
    public function index(Request $request, $take = 5)
    {
        if ($this->getUser()):
            return $this->redirectToRoute('post');
        endif;

        $currentPage = $request->query->get('p');
        $limit = $request->query->get('l');
        $page = (!isset($currentPage) || $currentPage <= 0) ? 1 : $currentPage;
        $skip = (($page - 1) * $take);
        $nbPosts = $this->getDoctrine()->getRepository(Post::class)->count(['public' => 'true']);
        $nbPages = ceil($nbPosts / $take);
        $posts = $this
            ->getDoctrine()
            ->getRepository(Post::class)
            ->findHomePage($skip, $take, true);

        return $this->render('post/public.posts.html.twig', [
            'posts' => $posts,
            'page' => $page,
            'nb_page' => $nbPages,
            'last_username' => $limit,
        ]);
    }

    /**
     * @Route("/post", name="post")
     * @param Request $request
     * @param int $take
     * @return Response
     */
    public function home(Request $request, $take = 5)
    {
        $currentPage = (int)$request->query->get('p');
        $limit = $request->query->get('l');
        $page = (!isset($currentPage) || $currentPage <= 0) ? 1 : $currentPage;
        $skip = (($page - 1) * $take);
        $posts = $this->getDoctrine()->getRepository(Post::class)->findHomePage($skip, $take);
        $nbPosts = count($this->getDoctrine()->getRepository(Post::class)->findAll());
        $nbPages = intval(ceil($nbPosts / $take));

        $isLastPage = $page === $nbPages;
        $response = new Response();

        if ($request->isXmlHttpRequest()):
            sleep(3); // simule une latence du serveur
            $response->headers->set('X-infrep-Is-last-page', $isLastPage ? '1' : '0');
            return $this->render('post/_loop.html.twig', ['posts' => $posts], $response);
        endif;

        return $this->render('post/posts.html.twig', [
            'posts' => $posts,
            'nb_page' => $nbPages,
            'page' => $page
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
     * @throws \Exception
     */
    public function create(Request $request)
    {
        $form = $this->createForm(PostType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()):
            // on crée un nouveau post
            $user = $this->getUser();
            /** @var Post $post */
            $post = $form->getData();
            /** @var UploadedFile $file */
            $file = $post->getAttachement();
            $ext = $file->guessExtension();
            $date = new \DateTime();
            $basename = 'post-attachment-' . $date->format('YmdHis');
            $filename = $basename . '.' . $ext;
            $file->move($this->getParameter('user_upload_folder'), $filename);
            $post->setAttachement($filename);
            $post->setUserId($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
            $this->addFlash('notice', 'Thx lord for your post !');

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

    /**
     * @Route("post/like/{post}", name="like_post")
     * @param Request $request
     * @param $post
     * @return RedirectResponse
     */
    public function like(Request $request, $post)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($post);
        if (empty($post)):
            throw $this->createNotFoundException('Post introuvable');
        endif;

        $user = $this->getUser();
        if (!$user->doesLike($post)):
            $user->like($post);
        else:
            $user->unlike($post);
        endif;
        $manager = $this->getDoctrine()->getManager();
        $manager->flush();
        if ($request->isXmlHttpRequest()):
            return new Response($post->getLikers()->count());
        endif;

        return $this->redirectToRoute('post');
    }
}
