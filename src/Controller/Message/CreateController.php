<?php

declare(strict_types=1);

namespace App\Controller\Message;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
#[Route('/messages')]
class CreateController
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * @ParamConverter("message", converter="fos_rest.request_body", options={"deserializationContext"={"groups"={"create"}}})
     * @Rest\View(statusCode=201, serializerGroups={"show"})
     *
     * @param Message $message
     * @param ConstraintViolationListInterface $validationErrors
     * @param UserInterface|null $author
     *
     * @return Message
     */
    #[Route('', methods: ['POST'])]
    public function create(
        Message $message,
        ConstraintViolationListInterface $validationErrors,
        ?UserInterface $author,
    ): Message {
        if ($validationErrors->count() > 0) {
            throw new BadRequestHttpException();
        }

        if (!($author instanceof User)) {
            throw new UnauthorizedHttpException('Bearer');
        }

        $message->setAuthor($author);

        $this->entityManager->persist($message);
        $this->entityManager->flush();

        return $message;
    }
}
