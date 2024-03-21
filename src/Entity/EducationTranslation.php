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
use App\Repository\EducationTranslationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Translatable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[UniqueEntity(fields: ["education", 'locale'])]
#[ORM\Entity(repositoryClass: EducationTranslationRepository::class)]
#[ApiResource(operations: [
    new Get(),
    new GetCollection(),
    new Post(),
    new Put(),
    new Delete()
],
    normalizationContext: ["groups" => ["education:read"]],
    denormalizationContext: ["groups" => ["education:write"]]
)]
#[ApiResource(operations: [
    new GetCollection(uriTemplate: '/educations/{educationId}/education_translations',
        uriVariables: [
            'educationId' => new Link(fromClass: Education::class, toProperty: 'education'),
        ],
    ),
    new Post(uriTemplate: '/educations/{educationId}/education_translations',
        uriVariables: [
            'educationId' => new Link(fromClass: Education::class, toProperty: 'education'),
        ],
        itemUriTemplate: '/educations/{educationId}/education_translations/{id}'
    ),
    new Get(uriTemplate: '/educations/{educationId}/education_translations/{id}',
        uriVariables: [
            'educationId' => new Link(fromClass: Education::class, toProperty: 'education'),
            'id' => new Link(fromClass: Education::class),
        ]
    )],
    normalizationContext: ["groups" => ["education_translation:read"]],
    denormalizationContext: ["groups" => ["education_translation:write"]],
)]
#[ApiFilter(filterClass: OrderFilter::class, properties: ['id', 'school', 'location'])]
#[ApiFilter(filterClass: SearchFilter::class, properties: ['id' => 'exact', 'degree' => 'partial', 'school' => 'partial', 'location' => 'partial', 'locale' => 'exact'])]
class EducationTranslation implements Translatable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["education:read", "education_translation:read"])]
    private ?int $id = null;
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["education_translation:read", "education_translation:write", "education:read", "education:write"])]
    private ?string $degree = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["education_translation:read", "education_translation:write", "education:read", "education:write",])]
    private ?string $school = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["education_translation:read", "education_translation:write", "education:read", "education:write"])]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["education_translation:read", "education_translation:write", "education:read", "education:write"])]
    private ?string $location = null;

    #[ORM\ManyToOne(targetEntity: Locale::class)]
    #[Groups(["education_translation:read", "education_translation:write", "education:read"])]
    private Locale $locale;

    #[ORM\ManyToOne(targetEntity: Education::class, inversedBy: "translations")]
    #[Groups(["education_translation:read", "education_translation:write"])]
    private Education $education;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDegree(): ?string
    {
        return $this->degree;
    }

    public function setDegree(string $degree): static
    {
        $this->degree = $degree;

        return $this;
    }

    public function getSchool(): ?string
    {
        return $this->school;
    }

    public function setSchool(?string $school): static
    {
        $this->school = $school;

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


    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($locale): self
    {
        $this->locale = $locale;
        return $this;
    }

    public function getEducation(): Education
    {
        return $this->education;
    }

    public function setEducation(Education $education): self
    {
        $this->education = $education;
        return $this;
    }

}
