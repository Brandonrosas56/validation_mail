<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SecopService
{
    public static function isValidSecopContract($documentoProveedor, $numeroContrato)
    {

        $apiUrl = "https://www.datos.gov.co/resource/jbjy-vk9h.json?"
            . "\$where=documento_proveedor='$documentoProveedor' AND id_contrato='$numeroContrato' AND estado_contrato='En ejecución'";

        try {
            $response = Http::get($apiUrl);
            $data = $response->json();

            if (isset($data['error']) || isset($data['message'])) {
                return false;
            }

            return is_array($data) && count($data) > 0;
        } catch (\Exception $e) {
            return false;
        }
    }
}
