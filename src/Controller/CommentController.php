<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Repository\BlogPostRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * List, add, and retrieve comments being related to a blog post.
 *
 * @author Raphael Matile <raphael.matile@raisenow.com>
 */
class CommentController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private CommentRepository $commentRepository;
    private SerializerInterface $serializer;

    public function __construct(
        EntityManagerInterface $entityManager,
        CommentRepository $commentRepository,
        SerializerInterface $serializer
    ) {
        $this->entityManager = $entityManager;
        $this->commentRepository = $commentRepository;
        $this->serializer = $serializer;
    }


    /**
     * @Route("/comments", name="comment_index", methods={"GET"})
     */
    public function index(): Response
    {
        $posts = $this->commentRepository->findAll();

        return new Response(
            $this->serializer->serialize(
                $posts,
                'json',
                [
                    'groups' => 'comment'
                ]
            ),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/json'
            ]
        );
    }

    /**
     * @Route("/comments", name="comment_new", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function new(Request $request): Response
    {
        $post = $this->serializer->deserialize(
            $request->getContent(),
            Comment::class,
            'json',
            [
                'groups' => 'comment'
            ]
        );
        
        $blogPost = $post->getBlogPost();
        if($blogPost->getCurrentState() == 'published') {
            $this->entityManager->persist($post);
            $this->entityManager->flush();
            return new Response(
                $this->serializer->serialize(
                    $post,
                    'json'
                ),
                Response::HTTP_CREATED,
                [
                    'Content-Type' => 'application/json'
                ]
            );
        } else {
            // return error response 
            return new Response(
                $this->serializer->serialize(
                    ['message' => 'The Post must be published in order to be able to comment'],
                    'json'
                ),
                // change the status Code
                // Should i change the status code to 422 or not ?
                // 404
                Response::HTTP_CONFLICT,
                [
                    'Content-Type' => 'application/json'
                ]
            );
        }
    }

    /**
     * @Route("/comments/{id}", name="comment_show", methods={"GET"})
     *
     * @param string $id The identifier of the comment to retrieve.
     *
     * @return Response
     */
    public function show(string $id): Response
    {
        $blogPost = $this->commentRepository->findOneBy(
            [
                'id' => $id
            ]
        );

        if (null === $blogPost) {
            throw new NotFoundHttpException('Could not find comment with given id');
        }

        return new Response(
            $this->serializer->serialize(
                $blogPost,
                'json',
                [
                    'groups' => 'comment'
                ]
            ),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/json'
            ]
        );
    }
}
