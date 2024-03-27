<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\CategoryTranslationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(fields: ["category", 'locale'])]
#[ORM\Entity(repositoryClass: CategoryTranslationRepository::class)]
#[ApiResource(operations: [
    new Get(),
    new GetCollection(),
    new Post(),
    new Put(),
    new Delete()
],
    normalizationContext: ["groups" => ["category_translation:read"]],
    denormalizationContext: ["groups" => ["category_translation:write"]],
)]
#[ApiResource(operations: [
    new GetCollection(uriTemplate: '/categories/{categoryId}/category_translations',
        uriVariables: [
            'categoryId' => new Link(fromClass: Category::class, toProperty: 'category'),
        ],
    ),
    new Post(uriTemplate: '/categories/{categoryId}/category_translations',
        uriVariables: [
            'categoryId' => new Link(fromClass: Category::class, toProperty: 'category'),
        ],
        itemUriTemplate: '/categories/{categoryId}/category_translations/{id}'
    ),
    new Get(uriTemplate: '/categories/{categoryId}/category_translations/{id}',
        uriVariables: [
            'categoryId' => new Link(fromClass: Category::class, toProperty: 'category'),
            'id' => new Link(fromClass: CategoryTranslation::class),
        ]
    )],
    normalizationContext: ["groups" => ["category_translation:read"]],
    denormalizationContext: ["groups" => ["category_translation:write"]],
)]
#[ApiFilter(filterClass: OrderFilter::class, properties: ['id', 'name', 'startAt', 'endAt'])]
#[ApiFilter(filterClass: SearchFilter::class, properties: ['id' => 'exact', 'name' => 'partial', 'locale' => 'exact'])]
class CategoryTranslation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["category_translation:read", "category_translation:write", "category:read", "post:read"])]
    private ?int $id = null;
    #[ORM\Column(length: 255)]
    #[Assert\Sequentially([
        new Assert\NotNull(),
        new Assert\Length(max: 250),
    ])]
    #[Groups(["category_translation:read", "category_translation:write", "category:read", "post:read"])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["category_translation:read", "category_translation:write", "category:read", "post:read"])]
    private ?string $description = null;
    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: "translations")]
    #[Groups(["category_translation:read", "category_translation:write"])]
    private Category $category;
    #[ORM\ManyToOne(targetEntity: Locale::class)]
    #[Groups(["category_translation:read", "category_translation:write", "category:read", "post:read"])]
    private Locale $locale;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getLocale(): Locale
    {
        return $this->locale;
    }

    public function setLocale(Locale $locale): self
    {
        $this->locale = $locale;
        return $this;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): self
    {
        $this->category = $category;
        return $this;
    }
}
