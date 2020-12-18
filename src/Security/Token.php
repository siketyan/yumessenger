<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;

class Token
{
    public static function create(User $user): string
    {
        return sha1($user->getId() . uniqid('', true) . mt_rand());
    }
}
