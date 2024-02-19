<?php

namespace App\Controller\Api;

use App\Repository\UserRepository;
use App\Service\ApiTokenGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class LoginController extends AbstractController
{
    #[Route('/login', name: 'api_login', methods: 'post')]
    public function login(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasher,
        ApiTokenGenerator $apiTokenGeneratorService
    ): JsonResponse {
        $username = $request->getPayload()->get('username');
        $password = $request->getPayload()->get('password');
        $user = $userRepository->findOneBy(['username' => $username]);

        if ($user) {
            if ($userPasswordHasher->isPasswordValid($user, $password)) {
                return $this->json([
                    'token' => $apiTokenGeneratorService->create($user),
                ]);
            }
        }

        return $this->json([
            'error' => 'Bad credentials',
        ], Response::HTTP_BAD_REQUEST);
    }
}
