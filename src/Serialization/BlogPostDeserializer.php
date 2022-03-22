<?php
declare(strict_types=1);


namespace App\Serialization;


use App\Entity\BlogPost;
use App\Repository\BlogPostRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * While creating comments, we allow to relate blog posts by just their ID.
 * To avoid cascading issues in the database, we lookup the blog post here
 * and retrieve it from the database.
 *
 * @author Raphael Matile <raphael.matile@raisenow.com>
 */
final class BlogPostDeserializer implements DenormalizerInterface
{
    private BlogPostRepository $blogPostRepository;

    public function __construct(BlogPostRepository $blogPostRepository)
    {
        $this->blogPostRepository = $blogPostRepository;
    }

    /**
     * @inheritDoc
     */
    public function supportsDenormalization($data, string $type, string $format = null)
    {
        // we support denormalization of blog posts if the data is an integer or a string looking like an int
        return $type === BlogPost::class && (is_numeric($data) || is_int($data));
    }

    /**
     * @inheritDoc
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $post = $this->blogPostRepository->find($data);

        if (null === $post) {
            throw new NotFoundHttpException('Blog post referenced in comment');
        }

        return $post;
    }
}
