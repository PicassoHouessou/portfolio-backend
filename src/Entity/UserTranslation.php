<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\UserTranslationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Put(),
        new Delete()
    ],

    normalizationContext: ["groups" => ["user:read"]],
    denormalizationContext: ["groups" => ["user:write"]],

)]
#[UniqueEntity(fields: ["user", 'locale'])]
#[ORM\Entity(repositoryClass: UserTranslationRepository::class)]
#[ApiFilter(filterClass: OrderFilter::class, properties: ['id', 'createdAt', 'updatedAt'])]
#[ApiFilter(filterClass: SearchFilter::class, properties: ['id' => 'exact',])]
#[ApiFilter(filterClass: DateFilter::class, properties: ['createdAt', 'updatedAt'])]
class UserTranslation
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["user:read", "user:write"])]
    private ?string $address = null;
    #[ORM\Column(nullable: true)]
    #[Groups(["user:read", "user:write"])]
    private ?string $driversLicense = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["user:read", "user:write"])]
    #[Gedmo\Translatable]
    private ?string $brief = null;

    #[ORM\ManyToOne(targetEntity: Locale::class)]
    #[Groups(["user:read", "user:write"])]
    private Locale $locale;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function isDriversLicense(): ?string
    {
        return $this->driversLicense;
    }

    public function setDriversLicense(?string $driversLicense): static
    {
        $this->driversLicense = $driversLicense;

        return $this;
    }

    public function getBrief(): ?string
    {
        return $this->brief;
    }

    public function setBrief(?string $brief): static
    {
        $this->brief = $brief;

        return $this;
    }


    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }
    
}
