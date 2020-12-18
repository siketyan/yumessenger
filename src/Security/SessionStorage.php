<?php

declare(strict_types=1);

namespace App\Security;

use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;

class SessionStorage
{
    private CacheInterface $cache;

    public function __construct(
        CacheInterface $cache,
    ) {
        $this->cache = $cache;
    }

    /**
     * @param string $token
     * @param Session $session
     *
     * @throws InvalidArgumentException
     */
    public function save(string $token, Session $session): void
    {
        $this->cache->delete($token);
        $this->cache->get($token, fn () => $session);
    }

    /**
     * @param string $token
     * @return Session|null
     *
     * @throws InvalidArgumentException
     */
    public function load(string $token): ?Session
    {
        return $this->cache->get($token, fn () => null);
    }
}
