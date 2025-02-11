<?php

namespace App\Services;

use GuzzleHttp\Client;

class GlpiApi
{
    protected $client;
    protected $apiUrl = 'https://glpi.medusaweb.co/apirest.php/'; 
    protected $apiToken = 'gYlK0Icz6Aa67qZW9VPCow7waqPXuQdL0Hr7weVp';

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->apiUrl,
            'headers' => [
                'Authorization' => 'gYlK0Icz6Aa67qZW9VPCow7waqPXuQdL0Hr7weVp' . $this->apiToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]
        ]);
    }

    /**
     * Inicia sesión en GLPI.
     *
     * @return mixed
     */
    public function initSession()
    {
        $response = $this->client->get('/initSession');
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Obtiene un recurso por su tipo e ID.
     *
     * @param string $resource
     * @param int $id
     * @return mixed
     */
    public function getResource(string $resource, int $id)
    {
        $response = $this->client->get("/$resource/$id");
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Crea un nuevo recurso.
     *
     * @param string $resource
     * @param array $data
     * @return mixed
     */
    public function createResource(string $resource, array $data)
    {
        $response = $this->client->post("/$resource", [
            'json' => $data
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Cierra la sesión en GLPI.
     *
     * @return mixed
     */
    public function killSession()
    {
        $response = $this->client->get('/killSession');
        return json_decode($response->getBody()->getContents(), true);
    }
}
