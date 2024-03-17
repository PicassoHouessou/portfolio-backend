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
use App\Repository\ExperienceTranslationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[UniqueEntity(fields: ["experience", 'locale'])]
#[ORM\Entity(repositoryClass: ExperienceTranslationRepository::class)]
#[ApiResource(operations: [
    new Get(),
    new GetCollection(),
    new Post(),
    new Put(),
    new Delete()
],
    normalizationContext: ["groups" => ["experience_translation:read"]],
    denormalizationContext: ["groups" => ["experience_translation:write"]],
)]
#[ApiResource(operations: [
    new GetCollection(uriTemplate: '/experiences/{experienceId}/experiences',
        uriVariables: [
            'experienceId' => new Link(fromClass: Experience::class, toProperty: 'experience'),
        ],
    ),
    new Post(uriTemplate: '/experiences/{experienceId}/experiences',
        uriVariables: [
            'experienceId' => new Link(fromClass: Experience::class, toProperty: 'experience'),
        ],
        itemUriTemplate: '/experiences/{experienceId}/experiences/{id}'
    ),
    new Get(uriTemplate: '/experiences/{experienceId}/experiences/{id}',
        uriVariables: [
            'experienceId' => new Link(fromClass: Experience::class, toProperty: 'experience'),
            'id' => new Link(fromClass: ExperienceTranslation::class),
        ]
    )],
    normalizationContext: ["groups" => ["experience_translation:read"]],
    denormalizationContext: ["groups" => ["experience_translation:write"]],
)]
#[ApiFilter(filterClass: OrderFilter::class, properties: ['id', 'title', 'startAt', 'endAt'])]
#[ApiFilter(filterClass: SearchFilter::class, properties: ['id' => 'exact', 'title' => 'partial', 'locale' => 'exact'])]
class ExperienceTranslation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["experience_translation:read", "experience_translation:write", "experience:read"])]
    private ?int $id = null;
    #[ORM\Column(length: 255)]
    #[Groups(["experience_translation:read", "experience_translation:write", "experience:read"])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["experience_translation:read", "experience_translation:write", "experience:read"])]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["experience_translation:read", "experience_translation:write", "experience:read"])]
    private ?string $location = null;
    #[ORM\ManyToOne(targetEntity: Experience::class, inversedBy: "translations")]
    #[Groups(["experience_translation:read", "experience_translation:write"])]
    private Experience $experience;
    #[ORM\ManyToOne(targetEntity: Locale::class)]
    #[Groups(["experience_translation:read", "experience_translation:write", "experience:read"])]
    private Locale $locale;

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

    public function getExperience(): Experience
    {
        return $this->experience;
    }

    public function setExperience(Experience $experience): void
    {
        $this->experience = $experience;
    }

    public function getLocale(): Locale
    {
        return $this->locale;
    }

    public function setLocale(Locale $locale): void
    {
        $this->locale = $locale;
    }
}
