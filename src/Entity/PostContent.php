<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post as PostMeta;
use ApiPlatform\Metadata\Put;
use App\Repository\PostContentRepository;
use Carbon\Carbon;
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
            new Delete(security: 'is_granted(\'delete\',object)'),
        ],
        normalizationContext: ["groups" => ["post_content:read"]],
        denormalizationContext: ["groups" => ["post_content:write"]],
    )]
#[UniqueEntity(fields: ["slug"])]
#[ApiFilter(filterClass: OrderFilter::class, properties: ['id', 'title', 'subtitle', 'externalUrl', 'createdAt', 'updatedAt'])]
#[ApiFilter(filterClass: SearchFilter::class, properties: ['id' => 'exact', 'title' => 'partial', 'subtitle' => 'partial', 'externalUrl' => 'partial', 'createdAt' => 'partial', 'type' => 'exact'])]
#[ApiFilter(filterClass: DateFilter::class, properties: ['createdAt', 'updatedAt'])]
#[ORM\Entity(repositoryClass: PostContentRepository::class)]
class PostContent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(["post_content:read", "post:read"])]
    private $id;

    #[ORM\Column(type: "string", length: 200)]
    #[Groups(["post_content:read", "post_content:write", "post:read"])]
    #[Assert\NotBlank()]
    #[Assert\Length(min: "10", max: "200")]
    private string $title;

    #[Gedmo\Slug(fields: ["title"])]
    #[ORM\Column(type: "string", length: 230)]
    #[Groups(["post_content:read", "post_content:write", "post:read"])]
    private string $slug;

    #[Gedmo\Timestampable(on: "create")]
    #[ORM\Column(type: "datetime")]
    #[Assert\DateTime()]
    #[Groups(["post_content:read", "post_content:write", "post:read"])]
    private ?\DateTimeInterface $createdAt = null;

    #[Gedmo\Timestampable()]
    #[ORM\Column(type: "datetime", nullable: true)]
    #[Assert\DateTime()]
    #[Groups(["post_content:read", "post_content:write", "post:read"])]
    private ?\DateTimeInterface $updatedAt = null;
    #[ORM\Column(type: "text", nullable: true)]
    #[Assert\NotBlank()]
    #[Assert\Length(max: "20000")]
    #[Groups(["post_content:read", "post_content:write", "post:read"])]
    private ?string $content = null;
    #[ORM\Column(type: "string", nullable: true)]
    #[Groups(["post_content:read", "post_content:write", "post:read"])]
    private ?string $externalUrl = null;

    #[ORM\ManyToOne(targetEntity: MediaObject::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[ApiProperty(types: ['https://schema.org/image'])]
    #[Groups(["post_content:read", "post_content:write", "post:read"])]
    private ?MediaObject $image = null;
    #[ORM\ManyToOne(targetEntity: Locale::class)]
    #[Groups(["post_content:read", "post_content:write", "post:read"])]
    private Locale $locale;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'contents')]
    #[Groups(["post_content:read", "post_content:write"])]
    private Post $post;


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


    public function getExternalUrl(): ?string
    {
        return $this->externalUrl;
    }

    public function setExternalUrl(?string $externalUrl): static
    {
        $this->externalUrl = $externalUrl;
        return $this;
    }

    public function getImage(): ?MediaObject
    {
        return $this->image;
    }

    public function setImage(?MediaObject $image): self
    {
        $this->image = $image;
        return $this;
    }


    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($locale): self
    {
        $this->locale = $locale;
        return $this;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function setPost(Post $post): self
    {
        $this->post = $post;
        return $this;
    }


}
