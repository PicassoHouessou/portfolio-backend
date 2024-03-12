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
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ApiResource(
    normalizationContext: ["groups" => ["project_category:read"]],
    denormalizationContext: ["groups" => ["project_category:write"]]
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
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Sequentially([
        new Assert\NotNull(),
        new Assert\Length(max: 250),
    ])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Gedmo\Slug(fields: ["name"])]
    #[Assert\Sequentially([
        new Assert\NotNull(),
        new Assert\Length(max: 250),
    ])]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Sequentially([
        new Assert\Length(max: 5000),
    ])]
    private ?string $description = null;

    #[ORM\Column(type: "datetime")]
    #[Gedmo\Timestampable(on: "create")]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: "datetime")]
    #[Gedmo\Timestampable()]
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