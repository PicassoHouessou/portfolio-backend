<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;;

use App\Repository\PostCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


#[ApiResource()]
#[ORM\Entity(repositoryClass: PostCategoryRepository::class)]

class PostCategory
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;


    #[ORM\Column(type: "string", length: 200)]
    #[Groups(["contact_us:read", "contact_us:write"])]
    #[Assert\NotBlank()]
    #[Assert\Length(max: "200")]
    private $name;


    #[ORM\Column(type: "text", nullable: true)]
    #[Assert\Length(max: "5000")]
    private $description;


    #[Gedmo\Timestampable(on: "create")]
    #[ORM\Column(type: "datetime")]
    #[Assert\NotBlank()]
    #[Assert\DateTime()]

    private $createdAt;


    #[Gedmo\Timestampable(on: "update")]
    #[ORM\Column(type: "datetime", nullable: true)]
    #[Assert\NotBlank()]
    #[Assert\DateTime()]
    private $updatedAt;


    #[ORM\ManyToMany(targetEntity: Post::class, mappedBy: "categories")]
    private $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

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
    /*
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
     */

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }
    /*
    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    */


    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->addCategory($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            $post->removeCategory($this);
        }

        return $this;
    }
}
