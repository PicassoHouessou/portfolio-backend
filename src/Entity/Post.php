<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post as PostMeta;
use ApiPlatform\Metadata\Put;
use App\Repository\PostRepository;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[
    ApiResource(
        operations: [
            new Get(security: 'is_granted(\'VIEW\',object)'),
            new GetCollection(),
            new PostMeta(security: 'is_granted(\'EDIT\',object)'),
            new Put(security: 'is_granted(\'EDIT\',object)'),
            new Delete(security: 'is_granted(\'EDIT\',object)'),
        ],
        normalizationContext: ["groups" => ["post:read"]],
        denormalizationContext: ["groups" => ["post:write"]],
    )]
#[UniqueEntity(fields: ["slug"])]
#[ApiFilter(filterClass: OrderFilter::class, properties: ['id', 'title', 'subtitle', 'externalUrl', 'createdAt', 'updatedAt'])]
#[ApiFilter(filterClass: SearchFilter::class, properties: ['id' => 'exact', 'title' => 'partial', 'subtitle' => 'partial', 'externalUrl' => 'partial', 'createdAt' => 'partial', 'author' => 'exact', 'type' => 'exact', 'type.name' => 'exact'])]
#[ApiFilter(BooleanFilter::class, properties: ['isEnabled'])]
#[ApiFilter(filterClass: DateFilter::class, properties: ['createdAt', 'updatedAt'])]
#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[Gedmo\Timestampable(on: "create")]
    #[ORM\Column(type: "datetime")]
    #[Assert\DateTime()]
    #[Groups(["post:read", "post:write"])]
    private ?\DateTimeInterface $createdAt = null;

    #[Gedmo\Timestampable()]
    #[ORM\Column(type: "datetime", nullable: true)]
    #[Assert\DateTime()]
    #[Groups(["post:read", "post:write"])]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: "post", orphanRemoval: true)]
    #[Groups(["post:read"])]
    private $comments;

    #[ORM\Column(type: "boolean")]
    #[Assert\NotBlank()]
    #[Groups(["post:read", "post:write"])]
    private bool $isEnabled = false;

    #[ORM\ManyToMany(targetEntity: Category::class)]
    #[Groups(["post:read"])]
    private $categories;

    #[ORM\ManyToMany(targetEntity: Tag::class)]
    #[Groups(["post:read"])]
    private $tags;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["post:read", "post:write"])]
    private ?User $author = null;

    #[ORM\Column(type: "boolean")]
    #[Groups(["post:read", "post:write"])]
    private bool $isStandalone = false; // Default to false (Iframe)
    #[ORM\Column(type: "string", nullable: true)]
    #[Groups(["post:read", "post:write"])]
    private ?string $externalUrl = null;


    #[ORM\ManyToOne(targetEntity: PostType::class)]
    #[Groups(["post:read", "post:write"])]
    private PostType $type;

    #[ORM\OneToMany(targetEntity: PostContent::class, mappedBy: "post")]
    #[Groups(["post:read"])]
    private Collection $contents;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->contents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUpdatedAtAgo()
    {

        return Carbon::instance($this->getUpdatedAt())->diffForHumans();
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

    public function getIsEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }


    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }
        return $this;
    }

    public function removeCategory(Category $category): self
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

    public function getContents(): Collection
    {
        return $this->contents;
    }

    public function addContent(PostContent $content): self
    {
        if (!$this->contents->contains($content)) {
            $this->contents[] = $content;
            $content->setPost($this);
        }

        return $this;
    }

    public function removeContent(PostContent $content): self
    {
        if ($this->contents->contains($content)) {
            $this->contents->removeElement($content);
            $content->setPost(null);
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

    public function getType(): PostType
    {
        return $this->type;
    }

    public function setType(PostType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getExternalUrl(): ?string
    {
        return $this->externalUrl;
    }

    public function setExternalUrl(?string $externalUrl): static
    {
        $this->externalUrl = $externalUrl;
        return $this;
    }


    public function getIsStandalone(): bool
    {
        return $this->isStandalone;
    }

    public function setIsStandalone(bool $isStandalone): static
    {
        $this->isStandalone = $isStandalone;
        return $this;
    }
}
