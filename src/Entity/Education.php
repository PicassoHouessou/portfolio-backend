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
use App\Repository\EducationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Translatable;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EducationRepository::class)]
#[ApiResource(
    normalizationContext: ["groups" => ["education:read"]],
    denormalizationContext: ["groups" => ["education:write"]]
)]
#[ApiResource(operations: [
    new GetCollection(uriTemplate: '/users/{userId}/educations',
        uriVariables: [
            'userId' => new Link(fromClass: User::class, toProperty: 'user'),
        ],
    ),
    new Post(uriTemplate: '/users/{userId}/educations',
        uriVariables: [
            'userId' => new Link(fromClass: User::class, toProperty: 'user'),
        ],
        itemUriTemplate: '/users/{userId}/educations/{id}'
    ),
    new Get(uriTemplate: '/users/{userId}/educations/{id}',
        uriVariables: [
            'userId' => new Link(fromClass: User::class, toProperty: 'user'),
            'id' => new Link(fromClass: Education::class),
        ]
    )],
    normalizationContext: ["groups" => ["experience:read"]],
    denormalizationContext: ["groups" => ["experience:write"]],
)]
#[ApiFilter(filterClass: OrderFilter::class, properties: ['id', 'school', 'startAt', 'location,', 'endAt'])]
#[ApiFilter(filterClass: SearchFilter::class, properties: ['id' => 'exact', 'degree' => 'partial', 'school' => 'partial', 'location' => 'partial'])]
#[ApiFilter(filterClass: DateFilter::class, properties: ['startAt', 'endAt'])]
class Education implements Translatable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["education:read", "user:read"])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(["education:read", "education:write", "user:read"])]
    private ?\DateTimeInterface $startAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(["education:read", "education:write", "user:read"])]
    private ?\DateTimeInterface $endAt = null;

    #[ORM\ManyToOne(inversedBy: 'educations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["education:read", "education:write"])]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'education', targetEntity: EducationTranslation::class)]
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


    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(EducationTranslation $language): static
    {
        if (!$this->translations->contains($language)) {
            $this->translations->add($language);
            $language->setExperience($this);
        }

        return $this;
    }

    public function removeTranslation(EducationTranslation $language): static
    {
        if ($this->translations->removeElement($language)) {
            // set the owning side to null (unless already changed)
            if ($language->getEducation() === $this) {
                $language->setEducation()(null);
            }
        }

        return $this;
    }
}
