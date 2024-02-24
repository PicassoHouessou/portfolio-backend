<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CVRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: CVRepository::class)]
#[ApiResource(
    normalizationContext: ["groups" => ["cv:read"]],
    denormalizationContext: ["groups" => ["cv:write"]],
)]
class CV
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["cv:read"])]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Vich\UploadableField(mapping: 'cv', fileNameProperty: 'name', size: 'size', mimeType: "mimeType", originalName: "originalName")]

    private ?File $file = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["cv:read"])]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["cv:read"])]
    private ?int $size = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['cv:read'])]
    public ?string $mimeType = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['cv:read'])]
    public ?string $originalName = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["cv:read"])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne]
    private ?Locale $language = null;

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $file
     */
    public function setFile(?File $file = null): void
    {
        $this->file = $file;

        if (null !== $file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setSize(?int $size): void
    {
        $this->size = $size;
    }

    public function getSize(): ?int
    {
        return $this->size;
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

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(?string $originalName): void
    {
        $this->originalName = $originalName;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): void
    {
        $this->mimeType = $mimeType;
    }


}
