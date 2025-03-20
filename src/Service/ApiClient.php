<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiClient
{
  private HttpClientInterface $httpClient;

  public function __construct(HttpClientInterface $httpClient)
  {
    $this->httpClient = $httpClient;
  }

  public function searchArtist(string $artistName, int $index = 0): array
  {
    $response = $this->httpClient->request('GET', 'https://api.deezer.com//search/artist/', [
      'query' => [
        'q' => $artistName,
        'index' => 0,
        'limit' => 1,
      ],
    ]);

    if ($response->getStatusCode() !== 200) {
      throw new \Exception('API request failed with status ' . $response->getStatusCode());
    }

    return $response->toArray();
  }
}