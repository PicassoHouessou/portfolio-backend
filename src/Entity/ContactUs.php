<?php

namespace App\Entity;

use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ContactUsRepository;
use  ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use App\State\ContactUsPostProcessor;
use Gedmo\Mapping\Annotation as Gedmo;


#[ApiResource(
    normalizationContext: ["groups" => ["contact_us:read"]],
    denormalizationContext: ["groups" => ["contact_us:write"]],
    operations: [
        new Get(),
        new Put(),
        new Delete(),
        new GetCollection(),
        new Post( processor: ContactUsPostProcessor::class),
    ]

)]
#[ApiFilter(SearchFilter::class, properties: ["subject", "partial", "fullName", "partial"])]
#[ORM\Entity(repositoryClass: ContactUsRepository::class)]
class ContactUs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", length: 200)]
    #[Groups(["contact_us:read", "contact_us:write"])]
    #[Assert\NotBlank()]
    #[Assert\Length(
        max: 200
    )]
    private $fullName;

    #[ORM\Column(type: "string", length: 200)]
    #[Groups(["contact_us:read", "contact_us:write"])]
    #[Assert\NotBlank()]
    #[Assert\Email()]

    private $email;

    #[ORM\Column(type: "string", length: 200)]
    #[Groups(["contact_us:read", "contact_us:write"])]
    #[Assert\NotBlank()]
    #[Assert\Length(
        max: 200
    )]
    private $subject;

    #[ORM\Column(type: "text")]
    #[Groups(["contact_us:read", "contact_us:write"])]
    #[Assert\NotBlank()]
    #[Assert\Length(
        min: 20,
        max: 5000
    )]
    private $message;

    #[Gedmo\Timestampable(on: "create")]
    #[ORM\Column(type: "datetime")]
    #[Groups(["contact_us:read"])]

    private $createdAt;


    /*
     * Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3
     * Groups(["contact_us:write"])]
     * Assert\NotBlank()]
     *
    public $recaptcha;
    */

    public function getId(): ?int
    {
        return $this->id;
    }

    /*

    public function __construct()
    {
        $this->createdAt = new \Datetime();
    }
    */

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getCreatedAtAgo($var = null): string
    {
        return Carbon::instance($this->getCreatedAt())->diffForHumans();
    }
    /*
        public function setCreatedAt(\DateTimeInterface $createdAt): self
        {
            $this->createdAt = $createdAt;

            return $this;
        }
        */
}
