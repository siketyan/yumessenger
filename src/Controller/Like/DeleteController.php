<?php

declare(strict_types=1);

namespace App\Controller\Like;

use App\Entity\Like;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
#[Route('/likes')]
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
     * @param Like $like
     *
     * @return void
     */
    #[Route('/{id}', methods: ['DELETE'])]
    public function create(Like $like): void
    {
        $this->entityManager->remove($like);
        $this->entityManager->flush();
    }
}
