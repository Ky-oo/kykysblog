<?php

namespace App\Message;

class CreatePdf
{
  public function __construct(
    private readonly int $postId,
    private readonly string $outputPath
  ) {
  }

  public function getPostId(): int
  {
    return $this->postId;
  }

  public function getOutputPath(): string
  {
    return $this->outputPath;
  }
}