<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\SessionRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=SessionRepository::class)
 */
class Session
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    #[Groups(['show'])]
    private string $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    #[Groups(['show'])]
    private User $user;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Groups(['show'])]
    private DateTimeInterface $expiresAt;

    public function __construct(User $user, DateTimeInterface $expiresAt)
    {
        $this->user = $user;
        $this->expiresAt = $expiresAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getExpiresAt(): DateTimeInterface
    {
        return $this->expiresAt;
    }
}
