<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\PostTypeRepository;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    normalizationContext: ["groups" => ["post_type:read"]],
    denormalizationContext: ["groups" => ["post_type:write"]],
)]
#[UniqueEntity(fields: ["title"])]
#[ApiFilter(filterClass: OrderFilter::class, properties: ['id', 'title', 'subtitle', 'url', 'createdAt', 'updatedAt'])]
#[ApiFilter(filterClass: SearchFilter::class, properties: ['id' => 'exact', 'title' => 'partial', 'subtitle' => 'partial', 'url' => 'partial', 'createdAt' => 'partial'])]
#[ApiFilter(filterClass: DateFilter::class, properties: ['createdAt', 'updatedAt'])]
#[ORM\Entity(repositoryClass: PostTypeRepository::class)]
class PostType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", length: 200)]
    #[Groups(["post_type:read", "post_type:write"])]
    #[Assert\NotBlank()]
    #[Assert\Length(min: "10", max: "200")]
    private $name;

    #[Gedmo\Slug(fields: ["name"])]
    #[ORM\Column(type: "string", length: 230)]
    private $slug;

    #[Gedmo\Timestampable(on: "create")]
    #[ORM\Column(type: "datetime")]
    #[Assert\DateTime()]
    private $createdAt;

    #[Gedmo\Timestampable()]
    #[ORM\Column(type: "datetime", nullable: true)]
    #[Assert\DateTime()]
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getName(): string
    {
        return $this->name;
    }


    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

}
