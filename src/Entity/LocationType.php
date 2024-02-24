<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LocationTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LocationTypeRepository::class)]
#[ApiResource(
    normalizationContext: ["groups" => ["locationtype:read"]],
    denormalizationContext: ["groups" => ["locationtype:write"]],
)]
class LocationType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["locationtype:read", "locationtype:write","experience:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["locationtype:read", "locationtype:write","experience:read"])]
    private ?string $name = null;

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
}
