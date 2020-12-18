<?php

declare(strict_types=1);

namespace App\Controller\Message;

use App\Entity\Message;
use App\Repository\MessageRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
#[Route('/messages')]
class ListController
{
    private MessageRepository $repository;

    public function __construct(
        MessageRepository $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * @Rest\View(serializerGroups={"show"})
     *
     * @return Message[]
     */
    #[Route('', methods: ['GET'])]
    public function show(): array
    {
        return $this->repository->findRecent();
    }
}
