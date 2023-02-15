<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Enum\GroupeType;
use App\Entity\AttributionPoints;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 255, enumType: GroupeType::class, nullable: true)]
    private ?GroupeType $groupe = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: AttributionPoints::class)]
    private Collection $attributionPoints;

    public function __construct()
    {
        $this->attributionPoints = new ArrayCollection();
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
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
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
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
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

    public function getGroupe(): ?GroupeType
    {

        return $this->groupe;
    }

    public function getDisplayGroupe(): ?string
    {

        return (!is_null($this->groupe)) ? $this->groupe->name : null;
    }

    public function setGroupe(?GroupeType $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * @return Collection<int, AttributionPoints>
     */
    public function getAttributionPoints(): Collection
    {
        return $this->attributionPoints;
    }

    public function addAttributionPoints(AttributionPoints $attributionPoint): self
    {
        if (!$this->attributionPoints->contains($attributionPoint)) {
            $this->attributionPoints->add($attributionPoint);
            $attributionPoint->setUser($this);
        }

        return $this;
    }

    public function removeAttributionPoints(AttributionPoints $attributionPoint): self
    {
        if ($this->attributionPoints->removeElement($attributionPoint)) {
            // set the owning side to null (unless already changed)
            if ($attributionPoint->getUser() === $this) {
                $attributionPoint->setUser(null);
            }
        }

        return $this;
    }

    public function getDisplayName(): string
    {
        return $this->email;
    }

    private function getActiveAttribution(): Collection
    {
        $attributions = new ArrayCollection();
        if (!$this->attributionPoints->isEmpty()) {
            $attributions = $this->attributionPoints->filter(function (AttributionPoints $attribution) {
                return $attribution->getActiveAt() < new \DateTime();
            });
        }
        return $attributions;
    }

    public function computedTotalpoints(): int
    {
        $total = 0;
        $attributions_list = $this->getActiveAttribution();
        if ($attributions_list) {
            foreach ($attributions_list as $attribution) {
                $total += $attribution->getPoints();
            }
        }

        return $total;
    }
}
