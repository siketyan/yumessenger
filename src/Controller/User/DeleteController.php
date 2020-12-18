<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
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
