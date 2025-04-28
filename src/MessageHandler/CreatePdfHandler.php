<?php

namespace App\MessageHandler;

use App\Message\CreatePdf;
use App\Service\PdfService;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Post;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreatePdfHandler
{
  public function __construct(
    private readonly EntityManagerInterface $entityManager,
    private readonly PdfService $pdfService
  ) {
  }

  public function __invoke(CreatePdf $message): void
  {
    $post = $this->entityManager->getRepository(Post::class)->find($message->getPostId());

    if (!$post) {
      throw new \RuntimeException('Post not found');
    }

    $this->pdfService->generateArticlePdf($post, $message->getOutputPath());
  }
}