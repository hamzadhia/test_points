<?php

namespace App\Entity;

use App\Repository\AttributionPointsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AttributionPointsRepository::class)]
#[ORM\HasLifecycleCallbacks]
class AttributionPoints
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'attributionPoint')]
    private ?User $user = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?\DateTimeImmutable $activeAt = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?int $points = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActiveAt(): ?\DateTimeImmutable
    {
        return $this->activeAt;
    }

    public function setActiveAt(?\DateTimeImmutable $activeAt): self
    {
        $this->activeAt = $activeAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): self
    {
        $this->points = $points;

        return $this;
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
}
