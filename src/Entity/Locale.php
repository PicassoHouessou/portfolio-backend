<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\LocaleRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [new Get(), new GetCollection(), new Post()],
    normalizationContext: ['groups' => ['language:read']],
    denormalizationContext: ['groups' => ['language:write']],
    extraProperties: [
        'standard_put' => false,
    ])]
#[UniqueEntity('code')]
#[ORM\Entity(repositoryClass: LocaleRepository::class)]
#[ApiFilter(filterClass: OrderFilter::class, properties: ['id', 'code', 'description', 'startAt', 'endAt'])]
#[ApiFilter(filterClass: SearchFilter::class, properties: ['id' => 'exact', 'code' => 'exact', 'description' => 'partial',])]
#[ApiFilter(filterClass: DateFilter::class, properties: ['startAt', 'endAt'])]
class Locale
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["language:read", 'user_translation:read', "education_translation:read", "experience_translation:read", "user:read"])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 5)]
    #[Groups(["language:read", "language:write", "user_translation:read", "education_translation:read", "experience_translation:read"])]
    #[Assert\Sequentially([
        new Assert\NotBlank,
        new Assert\Length(max: 5)
    ])]
    private string $code;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["language:read", "language:write", "user_translation:read", "education_translation:read", "experience_translation:read"])]
    #[Assert\Length(max: 255)]
    private ?string $description;

    #[Gedmo\Timestampable(on: "create")]
    #[ORM\Column(type: "datetime")]
    #[Groups(["user:read", "user:write"])]
    private \DateTimeInterface $createdAt;

    #[Gedmo\Timestampable(on: "update")]
    #[ORM\Column(type: "datetime", nullable: true)]
    #[Groups(["user:read", "user:write"])]
    private \DateTimeInterface $updatedAt;

    #[ORM\Column(type: 'boolean', options: ["default" => false])]
    private $isDefault = false;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIsDefault()
    {
        return $this->isDefault;
    }

    /**
     * @param bool $default
     */
    public function setIsDefault($default = false)
    {
        $this->isDefault = $default;
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

}
