<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Predis\Response\ResponseInterface;
use GuzzleHttp\Exception\RequestException;

class GLPIService
{
    protected $client;
    protected string|null $sessionToken = null;

    // Constante para la ruta del endpoint
    private const INIT_SESSION_ENDPOINT = 'initSession';

    private const TICKET_ENDPOINT = 'Ticket';

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
            $response = $this->testConnection();

            if (!isset($response['session_token'])) {
                throw new \Exception("Error: No se pudo obtener el session_token. Respuesta: " . json_encode($response));
            }
            
            $this->sessionToken = $response['session_token'];
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

            $body = $response->getBody()->getContents();
            // Decodificar y validar el contenido JSON
            $decoded = json_decode($body, true);
            return $decoded;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }
    function getTicketInfo(int $id)
    {
        $sessionToken = $this->testConnection()['session_token'];

        try {
            $response = $this->client->get(self::TICKET_ENDPOINT . "/{$id}", [
                'headers' => [
                    'Session-Token' => $sessionToken
                ],
            ]);
            $body = $response->getBody()->getContents();

            // Decodificar y validar el contenido JSON
            $decoded = json_decode($body, true);
            return $decoded;
        } catch (\Throwable $th) {
            print_r($th->getMessage());
        }
    }

    public function getTicketFollowByLastResponse(int $ticketId)
    {

        try {
            // Obtener el seguimiento (ITILFollowup) del ticket
            $response = $this->client->get("/apirest.php/Ticket/{$ticketId}/ITILFollowup", [
                'headers' => ['Session-Token' => $this->sessionToken],
            ]);

            $followups = json_decode($response->getBody()->getContents(), true);

            if (!empty($followups)) {
                // Ordenar por fecha descendente y obtener el último seguimiento
                usort($followups, fn($a, $b) => strtotime($b['date_creation']) - strtotime($a['date_creation']));
                return reset($followups);
            }else{
                return [];
            }
        } catch (\Exception $e) {
            error_log("Error al procesar ticket {$ticketId}: " . $e->getMessage());
        }
    }
}
