<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post as PostMeta;
use ApiPlatform\Metadata\Put;
use App\Repository\UserLanguageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserLanguageRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new PostMeta(security: "is_granted('ROLE_USER')"),
        new Put(security: "is_granted('ROLE_USER')"),
        new Patch(security: "is_granted('ROLE_USER')"),
        new Delete(security: "is_granted('ROLE_ADMIN')"),
    ],
    normalizationContext: ["groups" => ["userlanguage:read"]],
    denormalizationContext: ["groups" => ["userlanguage:write"]],
)]
class UserLanguage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["userlanguage:read", "user:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["userlanguage:read", "userlanguage:write", "user:read"])]
    private ?string $proficiency = null;

    #[ORM\ManyToOne(inversedBy: 'languages')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["userlanguage:read", "userlanguage:write"])]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["userlanguage:read", "userlanguage:write", "user:read"])]
    private ?Locale $language = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProficiency(): ?string
    {
        return $this->proficiency;
    }

    public function setProficiency(?string $proficiency): static
    {
        $this->proficiency = $proficiency;

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

    public function getLanguage(): ?Locale
    {
        return $this->language;
    }

    public function setLanguage(?Locale $language): static
    {
        $this->language = $language;

        return $this;
    }
}
