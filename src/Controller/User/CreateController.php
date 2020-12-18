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
class CreateController
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * @ParamConverter("user", converter="fos_rest.request_body", options={"deserializationContext"={"groups"={"create"}}})
     * @Rest\View(serializerGroups={"show"})
     *
     * @param User $user
     * @param ConstraintViolationListInterface $validationErrors
     *
     * @return User
     */
    #[Route('', methods: ['POST'])]
    public function create(User $user, ConstraintViolationListInterface $validationErrors): User
    {
        if ($validationErrors->count() > 0) {
            throw new BadRequestHttpException();
        }

        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw new ConflictHttpException(
                'The email address is already registered.',
                $e,
            );
        }

        return $user;
    }
}
