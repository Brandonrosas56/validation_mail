<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SecopService
{
    /**
     * Verifica si un contrato en SECOP es válido.
     *
     * @param string $documentoProveedor - Documento del proveedor
     * @param string $numeroContrato - Número del contrato
     * @return bool - Retorna true si el contrato es válido, false en caso contrario
     */
    public static function isValidSecopContract($documentoProveedor, $numeroContrato)
    {
        // Construcción de la URL de la API de SECOP
        $apiUrl = "https://www.datos.gov.co/resource/jbjy-vk9h.json?"
            . "\$where=documento_proveedor='$documentoProveedor' AND id_contrato='$numeroContrato' AND estado_contrato='En ejecución'";

        try {
            // Realiza la solicitud GET a la API
            $response = Http::get($apiUrl);
            $data = $response->json();

            // Verifica si la respuesta contiene un error
            if (isset($data['error']) || isset($data['message'])) {
                return false;
            }

            // Retorna true si se encontraron datos válidos en la respuesta
            return is_array($data) && count($data) > 0;
        } catch (\Exception $e) {
            return false;
        }
    }
}