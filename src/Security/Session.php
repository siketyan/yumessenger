<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use DateTimeInterface;
use Symfony\Component\Serializer\Annotation\Groups;

class Session
{
    #[Groups(['show'])]
    private User $user;

    #[Groups(['show'])]
    private DateTimeInterface $expiresAt;

    public function __construct(User $user, DateTimeInterface $expiresAt)
    {
        $this->user = $user;
        $this->expiresAt = $expiresAt;
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
