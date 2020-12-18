<?php

declare(strict_types=1);

namespace App\Controller\Like;

use App\DTO\MessageRef;
use App\Entity\Like;
use App\Entity\User;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[Route('/likes')]
class CreateController
{
    private EntityManagerInterface $entityManager;
    private MessageRepository $messageRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        MessageRepository $messageRepository,
    ) {
        $this->entityManager = $entityManager;
        $this->messageRepository = $messageRepository;
    }

    /**
     * @ParamConverter("messageRef", converter="fos_rest.request_body")
     * @Rest\View(statusCode=201, serializerGroups={"show"})
     *
     * @param MessageRef $messageRef
     * @param ConstraintViolationListInterface $validationErrors
     * @param UserInterface|null $user
     *
     * @return Like
     */
    #[Route('', methods: ['POST'])]
    public function create(
        MessageRef $messageRef,
        ConstraintViolationListInterface $validationErrors,
        ?UserInterface $user,
    ): Like {
        if ($validationErrors->count() > 0) {
            throw new BadRequestHttpException();
        }

        if (!($user instanceof User)) {
            throw new UnauthorizedHttpException('Bearer');
        }

        $message = $this->messageRepository->findOneBy([
            'id' => $messageRef->getMessageId(),
        ]);

        if ($message === null) {
            throw new NotFoundHttpException();
        }

        $likedUsers = array_map(
            fn (Like $like): User => $like->getUser(),
            iterator_to_array($message->getLikes()),
        );

        if (in_array($user, $likedUsers)) {
            throw new ConflictHttpException('Already liked.');
        }

        $like = new Like();
        $like->setUser($user);
        $like->setMessage($message);

        $this->entityManager->persist($like);
        $this->entityManager->persist($message);
        $this->entityManager->flush();

        return $like;
    }
}
