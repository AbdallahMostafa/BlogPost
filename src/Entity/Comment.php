<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"comment"})
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"comment"})
     */
    private string $authorName;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"comment"})
     */
    private string $authorEmail;

    /**
     * @ORM\Column(type="text")
     *
     * @Groups({"comment"})
     */
    private string $content;

    /**
     * @ORM\ManyToOne(targetEntity="BlogPost", inversedBy="comments")
     *
     * @Groups({"comment"})
     */
    private ?BlogPost $blogPost;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    public function setAuthorName(string $authorName): self
    {
        $this->authorName = $authorName;

        return $this;
    }

    public function getAuthorEmail(): string
    {
        return $this->authorEmail;
    }

    public function setAuthorEmail(string $authorEmail): self
    {
        $this->authorEmail = $authorEmail;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getBlogPost(): ?BlogPost
    {
        return $this->blogPost;
    }

    public function setBlogPost(?BlogPost $blogPost): void
    {
        $this->blogPost = $blogPost;
    }
}
