<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

#[Route('/api', name: 'api_')]
class ApiUserController extends AbstractController
{
  #[Route('/login', name: 'api_login', methods: ['POST'])]
  public function login(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, JWTTokenManagerInterface $jwtManager): JsonResponse
  {
    $data = json_decode($request->getContent(), true);

    if (!isset($data['email']) || !isset($data['password'])) {
      return new JsonResponse(['error' => 'Missing credentials'], Response::HTTP_BAD_REQUEST);
    }

    $user = $userRepository->findOneBy(['email' => $data['email']]);

    if (!$user || !$passwordHasher->isPasswordValid($user, $data['password'])) {
      return new JsonResponse(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
    }

    $token = $jwtManager->create($user);

    return new JsonResponse([
      'token' => $token
    ]);
  }

  #[Route('/register', name: 'register', methods: ['POST'])]
  public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): JsonResponse
  {
    $data = json_decode($request->getContent(), true);

    if (!isset($data['email']) || !isset($data['password'])) {
      return new JsonResponse(['error' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
    }

    $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
    if ($existingUser) {
      return new JsonResponse(['error' => 'User already exists'], Response::HTTP_CONFLICT);
    }

    $user = new User();
    $user->setEmail($data['email']);
    $user->setPassword($passwordHasher->hashPassword($user, $data['password']));
    $user->setRoles(['ROLE_USER']);

    $entityManager->persist($user);
    $entityManager->flush();

    return new JsonResponse([
      'message' => 'User created successfully',
      'user' => [
        'id' => $user->getId(),
        'email' => $user->getEmail()
      ]
    ], Response::HTTP_CREATED);
  }
}