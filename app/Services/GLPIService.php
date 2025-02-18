<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Predis\Response\ResponseInterface;

class GLPIService
{
    protected $client;
    protected string|null $sessionToken = null;

    // Constante para la ruta del endpoint
    private const INIT_SESSION_ENDPOINT = '/apirest.php/initSession';

    private const TICKET_ENDPOINT = '/apirest.php/Ticket';

    public function __construct()
    {
        try {
            //code...
            $this->client = new Client([
                'base_uri' => config('app.glpi.glpi_api_url'), // URL base de la API
                'headers' => [
                    'Authorization' => 'Basic ' . config('app.glpi.glpi_credentials'),
                    'App-Token' => config('app.glpi.glpi_app_token'),
                    'Content-Type' => 'application/json',
                ],
            ]);
            $this->sessionToken = $this->testConnection()['session_token'];
        } catch (\Throwable $th) {
            print_r($th->getMessage());
        }
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

    function createTicket(array $attributes)
    {

        try {
            //code...
            $response = $this->client->post(self::TICKET_ENDPOINT, [
                'headers' => [
                    'Session-Token' => $this->sessionToken
                ],
                'body' => json_encode($attributes)
            ]);
            return $response;
        } catch (\Throwable $th) {
            print_r($th->getMessage());
        }
    }
    function getTicketInfo(array $response)
    {
        $sessionToken = $this->testConnection()['session_token'];

        try {
            $response = $this->client->get(self::TICKET_ENDPOINT."/{$response['id']}", [
                'headers' => [
                    'Session-Token' => $sessionToken
                ],
            ]);
            return $response;
        } catch (\Throwable $th) {
            print_r($th->getMessage());
        }
    }
}
