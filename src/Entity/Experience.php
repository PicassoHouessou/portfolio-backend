<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\ExperienceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ExperienceRepository::class)]
#[ApiResource(operations: [
    new Get(), new GetCollection(),
    new Post(), new Put(),
],
    normalizationContext: ["groups" => ["experience:read"]],
    denormalizationContext: ["groups" => ["experience:write"]],
)]
#[ApiResource(operations: [
    new GetCollection(uriTemplate: '/users/{userId}/experiences',
        uriVariables: [
            'userId' => new Link(fromClass: User::class, toProperty: 'user'),
        ],
    ),
    new Post(uriTemplate: '/users/{userId}/experiences',
        uriVariables: [
            'userId' => new Link(fromClass: User::class, toProperty: 'user'),
        ],
        itemUriTemplate: '/users/{userId}/experiences/{id}'
    ),
    new Get(uriTemplate: '/users/{userId}/experiences/{id}',
        uriVariables: [
            'userId' => new Link(fromClass: User::class, toProperty: 'user'),
            'id' => new Link(fromClass: Experience::class),
        ]
    )],
    normalizationContext: ["groups" => ["experience:read"]],
    denormalizationContext: ["groups" => ["experience:write"]],
)]
#[ApiFilter(filterClass: OrderFilter::class, properties: ['id', 'title', 'company', 'startAt', 'endAt'])]
#[ApiFilter(filterClass: SearchFilter::class, properties: ['id' => 'exact', 'title' => 'partial', 'company' => 'partial',])]
#[ApiFilter(filterClass: DateFilter::class, properties: ['startAt', 'endAt'])]
class Experience
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["experience:read", "experience:write", "user:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["experience:read", "experience:write", "user:read"])]
    private ?string $company = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(["experience:read", "experience:write", "user:read"])]
    private ?\DateTimeInterface $startAt = null;
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(["experience:read", "experience:write", "user:read"])]
    private ?\DateTimeInterface $endAt = null;
    #[ORM\ManyToOne(inversedBy: 'experiences')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["experience:read", "experience:write"])]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[Groups(["experience:read", "experience:write"])]
    private ?LocationType $locationType = null;

    #[ORM\OneToMany(mappedBy: 'experience', targetEntity: ExperienceTranslation::class)]
    #[Groups(["education:read"])]
    private $translations;

    public function __construct()
    {
        $this->translations = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): static
    {
        $this->company = $company;

        return $this;
    }


    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(?\DateTimeInterface $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeInterface $endAt): static
    {
        $this->endAt = $endAt;

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

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getLocationType(): ?LocationType
    {
        return $this->locationType;
    }

    public function setLocationType(?LocationType $locationType): static
    {
        $this->locationType = $locationType;

        return $this;
    }

    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(ExperienceTranslation $language): static
    {
        if (!$this->translations->contains($language)) {
            $this->translations->add($language);
            $language->setExperience($this);
        }

        return $this;
    }

    public function removeTranslation(ExperienceTranslation $language): static
    {
        if ($this->translations->removeElement($language)) {
            // set the owning side to null (unless already changed)
            if ($language->getExperience() === $this) {
                $language->setExperience(null);
            }
        }

        return $this;
    }
}
