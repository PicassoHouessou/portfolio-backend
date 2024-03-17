<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Repository\CategoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ApiResource(
    normalizationContext: ["groups" => ["category:read"]],
    denormalizationContext: ["groups" => ["category:write"]]
)]
#[ApiResource(operations: [
    new GetCollection(uriTemplate: '/projects/{projectId}/tags',
        uriVariables: [
            'projectId' => new Link(fromClass: Category::class, toProperty: 'project'),
        ],
    ),
    new Post(uriTemplate: '/projects/{projectId}/tags',
        uriVariables: [
            'projectId' => new Link(fromClass: Category::class, toProperty: 'project'),
        ],
        itemUriTemplate: '/projects/{projectId}/tags/{id}'
    ),
    new Get(uriTemplate: '/projects/{projectId}/tags/{id}',
        uriVariables: [
            'projectId' => new Link(fromClass: Category::class, toProperty: 'project'),
            'id' => new Link(fromClass: Category::class),
        ]
    )],
    normalizationContext: ["groups" => ["project_tag:read"]],
    denormalizationContext: ["groups" => ["project_tag:write"]],
)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["category:read", "post:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Sequentially([
        new Assert\NotNull(),
        new Assert\Length(max: 250),
    ])]
    #[Groups(["category:read", "category:write", "post:read"])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Gedmo\Slug(fields: ["name"])]
    #[Assert\Sequentially([
        new Assert\NotNull(),
        new Assert\Length(max: 250),
    ])]
    #[Groups(["category:read", "category:write", "post:read"])]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Sequentially([
        new Assert\Length(max: 5000),
    ])]
    #[Groups(["category:read", "category:write", "post:read"])]
    private ?string $description = null;

    #[ORM\Column(type: "datetime")]
    #[Gedmo\Timestampable(on: "create")]
    #[Groups(["category:read", "category:write"])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: "datetime")]
    #[Gedmo\Timestampable()]
    #[Groups(["category:read", "category:write"])]
    private ?\DateTimeInterface $updatedAt = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}