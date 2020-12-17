<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="guid", unique=true)
     * @ORM\GeneratedValue(strategy="UUID")
     */
    #[Groups(['show'])]
    private string $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\NotBlank]
    #[Groups(['show', 'create'])]
    private string $nickname;

    /**
     * @ORM\Column(type="ascii_string", length=255, unique=true)
     */
    #[Assert\Email]
    #[Groups(['show', 'create'])]
    private string $email;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Groups(['show'])]
    private DateTimeInterface $createdAt;

    public function getId(): string
    {
        return $this->id;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeInterface $createdAt = null): self
    {
        $this->createdAt = $createdAt ?? new DateTimeImmutable();

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function onCreate(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }
}
