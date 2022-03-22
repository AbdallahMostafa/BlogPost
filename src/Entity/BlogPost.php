<?php

namespace App\Entity;

use App\Repository\BlogPostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=BlogPostRepository::class)
 */
class BlogPost
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"blog_post", "comment"})
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"blog_post"})
     */
    private string $name;

    /**
     * @ORM\Column(type="text")
     *
     * @Groups({"blog_post"})
     */
    private string $content;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="blogPost")
     */
    private Collection $comments;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"blog_post"})
     */
    private $currentState;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
    // search for type hint
    public function getCurrentState()
    {
        return $this->currentState;
    }

    public function setCurrentState($currentState)
    {
        $this->currentState = $currentState;
    }
}
