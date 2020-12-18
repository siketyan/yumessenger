<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use App\Repository\UserRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/users')]
class ShowController
{
    private UserRepository $repository;

    public function __construct(
        UserRepository $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * @Rest\View()
     *
     * @param User $user
     *
     * @return User
     */
    #[Route('/{id}', methods: ['GET'])]
    public function show(User $user): User
    {
        return $user;
    }
}
