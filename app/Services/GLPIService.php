<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class GLPIService
{
    protected $client;

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

    function createTicket(array $attributes) :void {
        $sessionToken = $this->testConnection()['session_token'];

        if ($sessionToken) {
            try {
                //code...
                $reponse = $this->client->post(self::TICKET_ENDPOINT,[
                    'headers' => [
                        'Session-Token' => $sessionToken
                    ],
                    'body'=> json_encode($attributes)
                ]);
            } catch (\Throwable $th) {
                print_r( $th->getMessage());
            }
        }

    }
}
