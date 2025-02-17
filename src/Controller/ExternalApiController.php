<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route("/api/external")]
class ExternalApiController
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    #[Route("/positions", methods: ["GET"])]
    public function fetchPositions(): JsonResponse
    {
        try {
            $response = $this->httpClient->request('GET', 'https://ibillboard.com/api/positions');

            // Verifica si la solicitud fue exitosa
            if ($response->getStatusCode() !== 200) {
                return new JsonResponse(['error' => 'Error al obtener posiciones'], $response->getStatusCode());
            }

            $data = $response->toArray(); // Convierte la respuesta en un array de PHP

            return new JsonResponse($data);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}