<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Interfaces\BlogPostWorkflowInterface;
use App\Repository\BlogPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * List, add, and retrieve blog posts.
 *
 * @author Raphael Matile <raphael.matile@raisenow.com>
 */
class BlogPostController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private BlogPostRepository $blogPostRepository;
    private SerializerInterface $serializer;
    private BlogPostWorkflowInterface $blogPostInterface;

    public function __construct(
        EntityManagerInterface    $entityManager,
        BlogPostRepository        $blogPostRepository,
        SerializerInterface       $serializer,
        BlogPostWorkflowInterface $blogPostInterface
    )
    {
        $this->entityManager = $entityManager;
        $this->blogPostRepository = $blogPostRepository;
        $this->serializer = $serializer;
        $this->blogPostInterface = $blogPostInterface;
    }


    /**
     * @Route("/blog-posts", name="blog_post_index", methods={"GET"})
     */
    public function index(): Response
    {
        $posts = $this->blogPostRepository->findAll();

        return new Response(
            $this->serializer->serialize(
                $posts,
                'json',
                [
                    'groups' => 'blog_post'
                ]
            ),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/json'
            ]
        );
    }

    /**
     * @Route("/blog-posts", name="blog_post_new", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function new(Request $request): Response
    {
        $post = $this->serializer->deserialize(
            $request->getContent(),
            BlogPost::class,
            'json'
        );

        try {
            $this->blogPostInterface->changeBlogPostToInReview($post);
        } catch (Exception $exception) {
            return new Response(
                $this->serializer->serialize(
                    ['message' => $exception->getMessage()],
                    'json'
                ),
                // 5xx
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [
                    'Content-Type' => 'application/json'
                ]
            );
        }

        $this->entityManager->persist($post);

        $this->entityManager->flush();

        return new Response(
            $this->serializer->serialize(
                $post,
                'json',
                [
                    'groups' => 'blog_post'
                ]
            ),
            Response::HTTP_CREATED,
            [
                'Content-Type' => 'application/json'
            ]
        );
    }

    /**
     * @Route("/blog-posts/{id}", name="blog_post_show", methods={"GET"})
     *
     * @param string $id The identifier of the blog post to retrieve.
     *
     * @return Response
     */
    public function show(string $id): Response
    {
        $blogPost = $this->blogPostRepository->findOneBy(
            [
                'id' => $id
            ]
        );

        if (null === $blogPost) {
            throw new NotFoundHttpException('Could not find blog post with given id');
        }

        return new Response(
            $this->serializer->serialize(
                $blogPost,
                'json',
                [
                    'groups' => 'blog_post'
                ]
            ),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/json'
            ]
        );
    }

    /**
     * @Route("/blog-posts/{id}/publish", name="blog_post_publish", methods={"POST"})
     *
     * @param string $id The identifier of the blog post to apply the publishing state.
     *
     * @return Response
     */
    public function publishPost(string $id): Response
    {
        $blogPost = $this->blogPostRepository->findOneBy(
            [
                'id' => $id
            ]
        );

        if (null === $blogPost) {
            throw new NotFoundHttpException('Could not find blog post with given id');
        }

        try {
            $this->blogPostInterface->changeBlogPostToPublished($blogPost);
        } catch (Exception $exception) {
            return new Response(
                $this->serializer->serialize(
                    ['message' => $exception->getMessage()],
                    'json'
                ),
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [
                    'Content-Type' => 'application/json'
                ]
            );
        }

        $this->entityManager->persist($blogPost);
        $this->entityManager->flush();

        return new Response(
            $this->serializer->serialize(
                $blogPost,
                'json',
                [
                    'groups' => 'blog_post'
                ]
            ),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/json'
            ]
        );
    }

    /**
     * @Route("/blog-posts/{id}/discard", name="blog_post_discard", methods={"POST"})
     *
     * @param string $id The identifier of the blog post to apply discarding state.
     *
     * @return Response
     */
    public function discardPost(string $id): Response
    {
        $blogPost = $this->blogPostRepository->findOneBy(
            [
                'id' => $id
            ]
        );

        if (null === $blogPost) {
            throw new NotFoundHttpException('Could not find blog post with given id');
        }

        try {
            $this->blogPostInterface->changeBlogPostToDiscarded($blogPost);
        } catch (Exception $exception) {
            return new Response(
                $this->serializer->serialize(
                    ['message' => $exception->getMessage()],
                    'json'
                ),
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [
                    'Content-Type' => 'application/json'
                ]
            );
        }

        $this->entityManager->persist($blogPost);
        $this->entityManager->flush();

        return new Response(
            $this->serializer->serialize(
                $blogPost,
                'json',
                [
                    'groups' => 'blog_post'
                ]
            ),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/json'
            ]
        );
    }
}
