<?php

declare(strict_types=1);

namespace App\Controller\Message;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
#[Route('/messages')]
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
     * @param Message $message
     *
     * @return void
     */
    #[Route('/{id}', methods: ['DELETE'])]
    public function create(Message $message): void
    {
        $this->entityManager->remove($message);
        $this->entityManager->flush();
    }
}
