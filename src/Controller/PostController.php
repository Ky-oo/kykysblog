<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\CreatePostArtistType;
use App\Service\ApiClient;
use Symfony\Bundle\SecurityBundle\Security;
use App\Form\AddCommentType;
use App\Entity\Comment;

final class PostController extends AbstractController
{
    #[Route('/', name: 'app_post_index', methods: ['GET'])]
    #[Route('/post', name: 'app_post_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findBy([], ['creationDate' => 'DESC']);

        return $this->render('post/index.html.twig', [
            "isAdmin" => $this->isGranted('ROLE_ADMIN'),
            'posts' => $posts,
        ]);
    }

    #[Route('/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ApiClient $apiClient): Response
    {
        $form = $this->createForm(CreatePostArtistType::class, );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $artistName = $form->getData()["ArtistName"];
            $artistData = $apiClient->searchArtist($artistName)['data'][0];

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

            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('post/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/post/{id}', name: 'app_post_show', methods: ['GET'])]
    public function show(Post $post, Security $security): Response
    {
        $isAdminOrAuthor = $this->isGranted('ROLE_ADMIN') || $post->getAuthor() === $security->getUser();
        return $this->render('post/show.html.twig', [
            'post' => $post,
            'isAdminOrAuthor' => $isAdminOrAuthor,
            'commentForm' => $this->createForm(AddCommentType::class, new Comment())->createView(),
        ]);
    }

    #[Route('/post/{id}/edit', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {

        if (!$this->IsGranted("IsCreator", $post)) {
            return $this->redirectToRoute('app_product');
        }

        $form = $this->createForm(CreatePostArtistType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/post/{id}', name: 'app_post_delete', methods: ['POST'])]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {

        if (!$this->IsGranted("IsCreator", $post)) {
            return $this->redirectToRoute('app_product');
        }

        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
    }
}
