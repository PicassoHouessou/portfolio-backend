<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\TagRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ["groups" => ["project_tag:read"]],
    denormalizationContext: ["groups" => ["project_tag:write"]]
)]
#[UniqueEntity(fields: ["name"])]
#[ApiFilter(filterClass: OrderFilter::class, properties: ['id', 'name', 'createdAt', 'updatedAt'])]
#[ApiFilter(filterClass: SearchFilter::class, properties: ['name' => 'partial'])]
#[ApiFilter(filterClass: DateFilter::class, properties: ['createdAt', 'updatedAt'])]
#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", length: 200)]
    #[Assert\NotBlank()]
    #[Assert\Length(max: "200")]
    #[Groups(["tag:read", "tag:write"])]
    private $name;
    #[Gedmo\Slug(fields: ["name"])]
    #[ORM\Column(type: "string", length: 230)]
    private $slug;

    #[ORM\Column(type: "text", nullable: true)]
    #[Assert\Length(max: "5000")]
    #[Groups(["tag:read", "tag:write"])]
    private $description;

    #[ORM\Column(type: "datetime")]
    #[Gedmo\Timestampable(on: "create")]
    #[Assert\NotBlank()]
    #[Assert\Datetime()]
    #[Groups(["tag:read", "tag:write"])]
    private $createdAt;

    #[ORM\Column(type: "datetime", nullable: true)]
    #[Gedmo\Timestampable()]
    #[Assert\NotBlank()]
    #[Assert\Datetime()]
    #[Groups(["tag:read", "tag:write"])]
    private $updatedAt;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updatedTimestamps(): void
    {
        $this->updatedAt = new \DateTime('now');
        if ($this->getCreatedAt() === null) {
            $this->createdAt = new \DateTime('now');
        }
    }


}
