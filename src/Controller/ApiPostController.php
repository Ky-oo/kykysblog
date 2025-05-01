<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\ApiClient;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/post', name: 'api_posts_')]
class ApiPostController extends AbstractController
{
  #[Route('', name: 'index', methods: ['GET'])]
  public function index(PostRepository $postRepository): JsonResponse
  {
    $posts = $postRepository->findBy([], ['creationDate' => 'DESC']);

    return $this->json([
      'posts' => array_map(function ($post) {
        return [
          'id' => $post->getId(),
          'artistName' => $post->getArtistName(),
          'artistPicture' => $post->getArtistPicture(),
          'creationDate' => $post->getCreationDate()->format('Y-m-d H:i:s'),
          'author' => $post->getAuthor() ? $post->getAuthor()->getEmail() : null,
          'nbAlbums' => $post->getArtistDeezerNbAlbums(),
          'nbFans' => $post->getArtistDeezerNbFans()
        ];
      }, $posts)
    ]);
  }

  #[Route('/{id}', name: 'show', methods: ['GET'])]
  public function show(Post $post): JsonResponse
  {
    return $this->json([
      'post' => [
        'id' => $post->getId(),
        'artistId' => $post->getArtistId(),
        'artistName' => $post->getArtistName(),
        'artistPicture' => $post->getArtistPicture(),
        'artistDeezerPictures' => [
          'small' => $post->getArtistDeezerPictureSmall(),
          'medium' => $post->getArtistDeezerPictureMedium(),
          'big' => $post->getArtistDeezerPictureBig(),
          'xl' => $post->getArtistDeezerPictureXl()
        ],
        'stats' => [
          'nbAlbums' => $post->getArtistDeezerNbAlbums(),
          'nbFans' => $post->getArtistDeezerNbFans(),
          'hasRadio' => $post->isArtistDeezerRadio()
        ],
        'links' => [
          'deezer' => $post->getArtistLink(),
          'tracklist' => $post->getArtistDeezerTracklist()
        ],
        'type' => $post->getArtistDeezerType(),
        'creationDate' => $post->getCreationDate()->format('Y-m-d H:i:s'),
        'author' => $post->getAuthor() ? $post->getAuthor()->getEmail() : null
      ]
    ]);
  }

  #[Route('/admin/create', name: 'create', methods: ['POST'])]
  public function create(Request $request, EntityManagerInterface $entityManager, ApiClient $apiClient): JsonResponse
  {
    $data = json_decode($request->getContent(), true);

    if (!isset($data['artistName'])) {
      return $this->json(['error' => 'Missing artistName'], Response::HTTP_BAD_REQUEST);
    }

    try {
      $artistData = $apiClient->searchArtist($data['artistName'])['data'][0];

      $post = new Post();
      $post->setArtistId($artistData['id']);
      $post->setArtistName($artistData['name']);
      $post->setArtistLink($artistData['link']);
      $post->setArtistPicture($artistData['picture']);
      $post->setArtistDeezerPictureSmall($artistData['picture_small']);
      $post->setArtistDeezerPictureMedium($artistData['picture_medium']);
      $post->setArtistDeezerPictureBig($artistData['picture_big']);
      $post->setArtistDeezerPictureXl($artistData['picture_xl']);
      $post->setArtistDeezerNbAlbums($artistData['nb_album']);
      $post->setArtistDeezerNbFans($artistData['nb_fan']);
      $post->setArtistDeezerRadio($artistData['radio']);
      $post->setArtistDeezerTracklist($artistData['tracklist']);
      $post->setArtistDeezerType($artistData['type']);
      $post->setAuthor($this->getUser());
      $post->setCreationDate(new \DateTime());

      $entityManager->persist($post);
      $entityManager->flush();

      return $this->json([
        'message' => 'Post created successfully',
        'post' => [
          'id' => $post->getId(),
          'artistId' => $post->getArtistId(),
          'artistName' => $post->getArtistName(),
          'artistPicture' => $post->getArtistPicture(),
          'creationDate' => $post->getCreationDate()->format('Y-m-d H:i:s'),
          'author' => $post->getAuthor() ? $post->getAuthor()->getEmail() : null
        ]
      ], Response::HTTP_CREATED);

    } catch (\Exception $e) {
      return $this->json(['error' => 'Error creating post: ' . $e->getMessage()], Response::HTTP_BAD_REQUEST);
    }
  }

  #[Route(path: '/admin/{id}', name: 'delete', methods: ['DELETE'])]
  public function delete(Post $post, EntityManagerInterface $entityManager): JsonResponse
  {
    try {
      $entityManager->remove($post);
      $entityManager->flush();

      return $this->json(['message' => 'Post deleted successfully']);
    } catch (\Exception $e) {
      return $this->json(['error' => 'Error deleting post'], Response::HTTP_BAD_REQUEST);
    }
  }
}