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
use ApiPlatform\Metadata\Put;
use App\Repository\UserRepository;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[
    ApiResource(
        operations: [
            new Get(),
            new GetCollection(),
            new Post(securityPostDenormalize: 'is_granted(\'VIEW\',object)'),
            new Put(security: 'is_granted(\'EDIT\',object)'),
            new Put(security: 'is_granted(\'EDIT\',object)'),
        ],

        normalizationContext: ["groups" => ["user:read"]],
        denormalizationContext: ["groups" => ["user:write"]],

    )]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ["email"])]
#[UniqueEntity(fields: ["username"])]
#[ApiFilter(filterClass: OrderFilter::class, properties: ['id', 'email', 'username', 'firstName', 'lastName', 'roles', 'createdAt', 'updatedAt'])]
#[ApiFilter(filterClass: SearchFilter::class, properties: ['id' => 'exact', 'firstName' => 'partial', 'lastName' => 'partial', 'email' => 'partial', 'username' => 'partial', 'roles' => 'partial', 'translations.locale.code' => 'exact'])]
#[ApiFilter(filterClass: DateFilter::class, properties: ['createdAt', 'updatedAt'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(["user:read"])]
    private $id;


    #[ORM\Column(type: "string", length: 180, unique: true)]
    #[Groups(["user:read", "user:write"])]
    #[Assert\NotBlank()]
    #[Assert\Email()]
    private $email;


    #[ORM\Column(type: "json")]
    #[Groups(["user:read"])]
    private $roles = [];

    #[ORM\Column(type: "string")]
    #[Groups(["user:write"])]
    private $password;


    #[ORM\Column(type: "string", length: 255, unique: true)]
    #[Assert\NotBlank()]
    #[Groups(["user:read", "user:write"])]
    private $username;


    #[Gedmo\Timestampable(on: "create")]
    #[ORM\Column(type: "datetime")]
    #[Groups(["user:read"])]
    private ?\DateTimeInterface $createdAt;

    #[Gedmo\Timestampable(on: "update")]
    #[ORM\Column(type: "datetime", nullable: true)]
    #[Groups(["user:read"])]
    private ?\DateTimeInterface $updatedAt;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["user:read", "user:write"])]
    #[Assert\Length(max: 250)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["user:read", "user:write"])]
    #[Assert\Length(max: 250)]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(["user:read", "user:write"])]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["user:read", "user:write"])]
    private ?string $country = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["user:read", "user:write"])]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["user:read", "user:write"])]
    private ?string $phoneNumber = null;
    #[ORM\Column(nullable: true)]
    #[Groups(["user:read", "user:write"])]
    private ?bool $isAvailable = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserLanguage::class, orphanRemoval: true)]
    #[Groups(["user:read"])]
    private Collection $languages;
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserTranslation::class, orphanRemoval: true)]
    #[Groups(["user:read"])]
    private Collection $translations;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Experience::class, orphanRemoval: true)]
    #[Groups(["user:read"])]
    private Collection $experiences;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Education::class, orphanRemoval: true)]
    #[Groups(["user:read"])]
    private Collection $educations;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["user:read"])]
    private ?string $website = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["user:read"])]
    private ?string $github = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["user:read"])]
    private ?string $linkedin = null;

    public function __construct()
    {
        $this->languages = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->experiences = new ArrayCollection();
        $this->educations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * [see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->username;
    }

    /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * [see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * [see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * [see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * [see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * [see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    /*
    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    */

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    #[Groups(["user:read"])]
    public function getFullName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    #[Groups(["user:read"])]
    public function getBirthDateHuman()
    {
        if ($this->getBirthDate() === null) return "";
        return Carbon::instance($this->getBirthDate())->isoFormat("LL");

    }

    public function setBirthdate(?\DateTimeInterface $birthdate): static
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function isIsAvailable(): ?bool
    {
        return $this->isAvailable;
    }

    public function setIsAvailable(?bool $isAvailable): static
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    public function getLanguages(): Collection
    {
        return $this->translations;
    }

    public function addLanguage(UserLanguage $language): static
    {
        if (!$this->translations->contains($language)) {
            $this->translations->add($language);
            $language->setUser($this);
        }

        return $this;
    }

    public function removeLanguage(UserLanguage $language): static
    {
        if ($this->translations->removeElement($language)) {
            // set the owning side to null (unless already changed)
            if ($language->getUser() === $this) {
                $language->setUser(null);
            }
        }

        return $this;
    }


    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(UserTranslation $language): static
    {
        if (!$this->translations->contains($language)) {
            $this->translations->add($language);
            $language->setUser($this);
        }

        return $this;
    }

    public function removeTranslation(UserTranslation $language): static
    {
        if ($this->translations->removeElement($language)) {
            // set the owning side to null (unless already changed)
            if ($language->getUser() === $this) {
                $language->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Experience>
     */
    public function getExperiences(): Collection
    {
        return $this->experiences;
    }

    public function addExperience(Experience $experience): static
    {
        if (!$this->experiences->contains($experience)) {
            $this->experiences->add($experience);
            $experience->setUser($this);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): static
    {
        if ($this->experiences->removeElement($experience)) {
            // set the owning side to null (unless already changed)
            if ($experience->getUser() === $this) {
                $experience->setUser(null);
            }
        }

        return $this;
    }

    public function getEducations(): Collection
    {
        return $this->educations;
    }

    public function addEducation(Education $education): static
    {
        if (!$this->educations->contains($education)) {
            $this->educations->add($education);
            $education->setUser($this);
        }

        return $this;
    }

    public function removeEducation(Education $education): static
    {
        if ($this->educations->removeElement($education)) {
            // set the owning side to null (unless already changed)
            if ($education->getUser() === $this) {
                $education->setUser(null);
            }
        }

        return $this;
    }


    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): static
    {
        $this->website = $website;

        return $this;
    }

    public function getGithub(): ?string
    {
        return $this->github;
    }

    public function setGithub(?string $github): static
    {
        $this->github = $github;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): static
    {
        $this->linkedin = $linkedin;

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


    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    public function __toString(): string
    {
        return (string)$this->getId();
    }
}
