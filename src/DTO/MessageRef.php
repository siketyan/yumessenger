<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class MessageRef
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    private string $messageId;

    public function getMessageId(): string
    {
        return $this->messageId;
    }

    public function setMessageId(string $messageId): self
    {
        $this->messageId = $messageId;

        return $this;
    }
}
