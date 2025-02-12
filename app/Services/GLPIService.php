<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class GLPIService
{
    protected $client;

    // Constante para la ruta del endpoint
    private const INIT_SESSION_ENDPOINT = '/initSession';

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('GLPI_API_URL'), // URL base de la API
            'headers' => [
                'Authorization' => 'user_token ' . env('GLPI_API_USER_TOKEN'),
                'App-Token' => env('GLPI_APP_TOKEN'),
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * Probar la conexión con la API de GLPI
     *
     * @return array Respuesta de la API o error
     */
    public function testConnection(): array
    {
        try {
            // Hacemos una solicitud al endpoint initSession para probar la conexión
            $response = $this->client->get(self::INIT_SESSION_ENDPOINT);

            $body = $response->getBody()->getContents();

            // Decodificar y validar el contenido JSON
            $decoded = json_decode($body, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException('Error al decodificar la respuesta JSON: ' . json_last_error_msg());
            }

            return $decoded;
        } catch (RequestException $e) {
            // Capturar errores específicos de Guzzle
            return [
                'error' => 'Error en la solicitud HTTP: ' . $e->getMessage(),
                'status_code' => $e->hasResponse() ? $e->getResponse()->getStatusCode() : null,
            ];
        } catch (\Exception $e) {
            // Capturar cualquier otra excepción
            return ['error' => 'Error general: ' . $e->getMessage()];
        }
    }
}
