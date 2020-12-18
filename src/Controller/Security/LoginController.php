<?php

declare(strict_types=1);

namespace App\Controller\Security;

use App\DTO\LoginRequest;
use App\DTO\LoginResponse;
use App\Repository\UserRepository;
use App\Security\Session;
use App\Security\SessionStorage;
use App\Security\Token;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Cache\InvalidArgumentException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class LoginController
{
    private UserRepository $userRepository;
    private UserPasswordEncoderInterface $passwordEncoder;
    private EntityManagerInterface $entityManager;
    private SessionStorage $sessionStorage;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager,
        SessionStorage $sessionStorage,
    ) {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
        $this->sessionStorage = $sessionStorage;
    }

    /**
     * @ParamConverter("credential", converter="fos_rest.request_body")
     * @Rest\View(statusCode=201, serializerGroups={"show"})
     *
     * @param LoginRequest $credential
     * @param ConstraintViolationListInterface $validationErrors
     *
     * @return LoginResponse
     *
     * @throws InvalidArgumentException
     */
    #[Route('/auth', methods: ['POST'])]
    public function login(LoginRequest $credential, ConstraintViolationListInterface $validationErrors): LoginResponse
    {
        if ($validationErrors->count() > 0) {
            throw new BadRequestHttpException(
                'Insufficient parameters found.'
            );
        }

        $user = $this->userRepository->findOneBy([
            'email' => $credential->getEmail(),
        ]);

        if ($user !== null && $this->passwordEncoder->isPasswordValid($user, $credential->getPassword())) {
            $session = new Session(
                $user,
                (new DateTimeImmutable())->modify('+30 minutes'),
            );

            $this->entityManager->persist($session);
            $this->entityManager->flush();

            $token = Token::create($user);
            $this->sessionStorage->save($token, $session);

            return new LoginResponse(
                $token,
                $session,
            );
        }

        throw new BadRequestHttpException(
            'Invalid email address or password was provided.',
        );
    }
}
