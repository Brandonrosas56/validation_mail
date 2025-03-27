<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\RequestException;

class GLPIService
{
    protected $client;
    protected string|null $sessionToken = null;

    // Constantes para los endpoints de la API
    private const INIT_SESSION_ENDPOINT = 'initSession';
    private const GET_FULL_SESSION_ENDPOINT = 'getFullSession/';
    private const TICKET_ENDPOINT = 'Ticket';

    public function __construct()
    {
        try {
            // Configurar el cliente HTTP
            $this->client = new Client([
                'base_uri' => config('app.glpi.glpi_api_url'), // URL base de la API
                'headers' => [
                    'Authorization' => 'Basic ' . config('app.glpi.glpi_credentials'), // Autenticación básica
                    'App-Token' => config('app.glpi.glpi_app_token'), // Token de la aplicación
                    'Content-Type' => 'application/json', // Tipo de contenido
                ],
            ]);

            // Obtener el token de sesión
            $response = $this->initSession();

            if (!isset($response['session_token'])) {
                throw new \Exception("Error: No se pudo obtener el session_token. Respuesta: " . json_encode($response));
            }

            $this->sessionToken = $response['session_token'];
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            throw $th; // Relanzar la excepción para que sea manejada en un nivel superior
        }
    }
    /**
 * Obtener el ID de un usuario por su correo electrónico.
 *
 * @param string $email Correo electrónico del usuario
 * @return int|null ID del usuario o null si no se pudo obtener
 */
        public function getUserIdByEmail(string $email): ?int
        {
            try {
                $response = $this->client->get('User/', [
                    'headers' => [
                        'Session-Token' => $this->sessionToken,
                    ],
                    'query' => [
                        'searchText' => $email,
                    ],
                ]);

                $body = $response->getBody()->getContents();
                $decoded = json_decode($body, true);

                if (isset($decoded[0]['id'])) {
                    return $decoded[0]['id'];
                }

                Log::error('No se pudo obtener el user_id para el correo: ' . $email);
                return null;
            } catch (\Throwable $th) {
                Log::error('Error al obtener el user_id: ' . $th->getMessage());
                return null;
            }
        }

    /**
     * Iniciar sesión en la API de GLPI y obtener el token de sesión.
     *
     * @return array Respuesta de la API
     */
    public function initSession(): array
    {
        try {
            $response = $this->client->get(self::INIT_SESSION_ENDPOINT);
            $body = $response->getBody()->getContents();
            $decoded = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException('Error al decodificar la respuesta JSON: ' . json_last_error_msg());
            }

            return $decoded;
        } catch (RequestException $e) {
            Log::error('Error en la solicitud HTTP: ' . $e->getMessage());
            return ['error' => 'Error en la solicitud HTTP: ' . $e->getMessage()];
        } catch (\Exception $e) {
            Log::error('Error general: ' . $e->getMessage());
            return ['error' => 'Error general: ' . $e->getMessage()];
        }
    }

    /**
     * Obtener la información completa de la sesión actual.
     *
     * @return array Respuesta de la API
     */
    public function getFullSession(): array
    {
        try {
            $response = $this->client->get(self::GET_FULL_SESSION_ENDPOINT, [
                'headers' => [
                    'Session-Token' => $this->sessionToken, // Token de sesión
                ],
            ]);

            $body = $response->getBody()->getContents();
            $decoded = json_decode($body, true);

            Log::info('Respuesta de getFullSession:', $decoded);


            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException('Error al decodificar la respuesta JSON: ' . json_last_error_msg());
            }

            return $decoded;
        } catch (RequestException $e) {
            Log::error('Error en la solicitud HTTP: ' . $e->getMessage());
            return ['error' => 'Error en la solicitud HTTP: ' . $e->getMessage()];
        } catch (\Exception $e) {
            Log::error('Error general: ' . $e->getMessage());
            return ['error' => 'Error general: ' . $e->getMessage()];
        }
    }

    /**
     * Obtener el ID del usuario conectado (glpiID).
     *
     * @return int|null ID del usuario o null si no se pudo obtener
     */
    public function getGlpiID(): ?int
    {
        $sessionInfo = $this->getFullSession();

        Log::info('Información de la sesión de GLPI:', $sessionInfo);


        if (isset($sessionInfo['session']['glpiID'])) {
            return $sessionInfo['session']['glpiID'];
        }

        Log::error('No se pudo obtener el glpiID de la sesión.');
        return null;
    }

    /**
     * Crear un ticket en GLPI.
     *
     * @param array $attributes Atributos del ticket
     * @return array Respuesta de la API
     */
    public function createTicket(array $attributes): array
    {
        try {
            Log::info('Datos enviados a la API de GLPI:', $attributes);

            $response = $this->client->post(self::TICKET_ENDPOINT, [
                'headers' => [
                    'Session-Token' => $this->sessionToken, // Token de sesión
                ],
                'body' => json_encode($attributes), // Datos del ticket
            ]);

            $body = $response->getBody()->getContents();
            $decoded = json_decode($body, true);
            Log::info('Respuesta de la API de GLPI:', $decoded);

            return $decoded;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return ['error' => $th->getMessage()];
        }
    }

    /**
     * Obtener información de un ticket específico.
     *
     * @param int $id ID del ticket
     * @return array Respuesta de la API
     */
    public function getTicketInfo(int $id): array
    {
        try {
            $response = $this->client->get(self::TICKET_ENDPOINT . "/{$id}", [
                'headers' => [
                    'Session-Token' => $this->sessionToken, // Token de sesión
                ],
            ]);

            $body = $response->getBody()->getContents();
            $decoded = json_decode($body, true);
            return $decoded;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return ['error' => $th->getMessage()];
        }
    }

    /**
     * Obtener el último seguimiento (ITILFollowup) de un ticket.
     *
     * @param int $ticketId ID del ticket
     * @return array Último seguimiento o array vacío si no hay seguimientos
     */
    public function getDefaultGroupId(): int
{
    // Buscar grupo por nombre (ajusta según tu GLPI)
    $response = $this->glpiApi->search('Group', [
        'name' => 'Soporte Técnico' // Nombre exacto del grupo
    ]);
    
    return $response[0]['id'] ?? throw new \Exception('Grupo por defecto no encontrado');
}

public function getDefaultTechnicianId(): int
{
    // Buscar usuario técnico por email (ajusta según tu GLPI)
    $response = $this->glpiApi->search('User', [
        'email' => 'soporte@empresa.com' // Email del técnico
    ]);
    
    return $response[0]['id'] ?? throw new \Exception('Técnico por defecto no encontrado');
}
    
    
     public function closeTicket(int $id): array
     {
         Log::info("id tike", [$id]);
     
         try {
             if ($id <= 0) {
                 Log::error("Error: El id proporcionado no es válido", ['id' => $id]);
                 return ['error' => 'El id proporcionado no es válido.'];
             }
     
             $response = $this->client->put(self::TICKET_ENDPOINT . "/{$id}", [
                 'headers' => [
                     'Session-Token' => $this->sessionToken,
                     'Content-Type' => 'application/json',
                 ],
                 'json' => [
                     'input' => [
                         'id' => $id,  // Es importante incluir el ID del ticket
                         'status' => 5  // Estado 6 = Cerrado
                     ]
                 ]
             ]);
     
             if ($response->getStatusCode() !== 200) {
                 Log::error("Error al cerrar el ticket {$id}: Respuesta no exitosa", [
                     'id' => $id,
                     'status_code' => $response->getStatusCode(),
                     'response' => $response->getBody()->getContents(),
                 ]);
                 return ['error' => 'Error al cerrar el ticket: Respuesta no exitosa.'];
             }
     
             $body = $response->getBody()->getContents();
             $decoded = json_decode($body, true);
     
             Log::info("Ticket {$id} cerrado con éxito", ['ticket_id' => $id, 'response' => $decoded]);
     
             return $decoded;
         } catch (\Exception $e) {
             Log::error("Error al cerrar el ticket {$id}: " . $e->getMessage(), [
                 'ticket_id' => $id,
                 'error' => $e->getMessage(),
             ]);
             return ['error' => 'Error al procesar el ticket: ' . $e->getMessage()];
         }
     }
    }     