<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PostRepository;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


#[ApiResource(
    normalizationContext: ["groups" => ["post:read"]],
    denormalizationContext: ["groups" => ["post:write"]],
)]
#[ORM\Entity(repositoryClass: PostRepository::class)]

class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]

    private $id;

    #[ORM\Column(type: "string", length: 200)]
    #[Groups(["post:read", "post:write"])]
    #[Assert\NotBlank()]
    #[Assert\Length(min: "10", max: "200")]

    private $title;

    #[Gedmo\Slug(fields: ["title"])]
    #[ORM\Column(type: "string", length: 230)]

    private $slug;

    #[Gedmo\Timestampable(on: "create")]
    #[ORM\Column(type: "datetime")]
    #[Assert\DateTime()]
    private $createdAt;

    #[Gedmo\Timestampable()]
    #[ORM\Column(type: "datetime", nullable: true)]
    #[Assert\DateTime()]
    private $updatedAt;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: "post", orphanRemoval: true)]

    private $comments;

    #[ORM\Column(type: "text")]
    #[Groups(["post:read", "post:write"])]
    #[Assert\NotBlank()]
    #[Assert\Length(max: "20000")]

    private $content;

    #[ORM\Column(type: "boolean")]
    #[Groups(["post:read", "post:write"])]
    #[Assert\NotBlank()]
    private $isActivated = false;

    #[ORM\ManyToMany(targetEntity: PostCategory::class, inversedBy: "posts")]

    private $categories;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: "posts")]

    private $tags;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "posts")]
    #[ORM\JoinColumn(nullable: false)]

    private $author;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getCreatedAtAgo()
    {

        return Carbon::instance($this->getCreatedAt())->diffForHumans();
    }
    /*
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    */

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }
    /*
    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
*/

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getIsActivated(): ?bool
    {
        return $this->isActivated;
    }

    public function setIsActivated(bool $isActivated): self
    {
        $this->isActivated = $isActivated;

        return $this;
    }


    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(PostCategory $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(PostCategory $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }


    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
