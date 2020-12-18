<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Session;
use Symfony\Component\Serializer\Annotation\Groups;

class LoginResponse
{
    #[Groups(['show'])]
    private string $token;

    #[Groups(['show'])]
    private Session $session;

    public function __construct(
        string $token,
        Session $session,
    ) {
        $this->token = $token;
        $this->session = $session;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getSession(): Session
    {
        return $this->session;
    }
}
