<?php
namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;
use Twig\Environment;
use App\Entity\Post;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;



class PdfService
{

  public function __construct(
    private Environment $twig,
    private ParameterBagInterface $params
  ) {
  }

  public function generateArticlePdf(Post $post, string $filePath): void
  {
    $options = new Options();
    $options->set('defaultFont', 'Arial');

    $dompdf = new Dompdf($options);

    $html = $this->twig->render('post/show_pdf.html.twig', [
      'post' => $post,
    ]);

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    file_put_contents($filePath, $dompdf->output());
  }
}