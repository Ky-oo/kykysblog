<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\AddCommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CommentController extends AbstractController
{
    #[Route('/comment/{postId}', name: 'app_post_add_comment', methods: ['POST'])]
    public function addComment(int $postId, Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $post = $entityManager->getRepository(Post::class)->find($postId);

        if (!$post) {
            throw $this->createNotFoundException('Post not found.');
        }

        $comment = new Comment();
        $form = $this->createForm(AddCommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setPost($post);
            $comment->setAuthor($security->getUser());
            $comment->setCreationDate(new \DateTime());

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_post_show', ['id' => $postId]);
        }

        return $this->redirectToRoute('app_post_show', ['id' => $postId]);
    }

    #[Route('/comment/{id}/delete', name: 'app_comment_delete', methods: ['GET'])]
    public function deleteComment(Comment $comment, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($comment->getAuthor() !== $this->getUser()) {
            return $this->redirectToRoute('app_post_show', ['id' => $comment->getPost()->getId()]);
        }

        $entityManager->remove($comment);
        $entityManager->flush();

        return $this->redirectToRoute('app_post_show', ['id' => $comment->getPost()->getId()]);
    }
}