<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/users')]
class DeleteController
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * @Rest\View(statusCode=204)
     *
     * @param User $user
     *
     * @return void
     */
    #[Route('/{id}', methods: ['DELETE'])]
    public function create(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
