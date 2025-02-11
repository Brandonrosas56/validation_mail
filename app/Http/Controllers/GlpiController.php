<?php


namespace App\Http\Controllers;

use App\Services\GlpiApi;
use Illuminate\Http\Request;

class GlpiController extends Controller
{
    private $glpiApi;

    public function __construct(GlpiApi $glpiApi)
    {
        $this->glpiApi = $glpiApi;
    }

    public function createTicket(Request $request)
    {
        // Validar los datos del ticket
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'nullable|integer|min:1|max:5',
            'type' => 'nullable|integer',
            '_users_id_requester' => 'nullable|integer',
        ]);

        // Crear el ticket usando el servicio
        $response = $this->glpiApi->createTicket($validated);

        if (isset($response['error']) && $response['error']) {
            return response()->json([
                'error' => 'No se pudo crear el ticket',
                'details' => $response['message']
            ], $response['status']);
        }

        return response()->json([
            'message' => 'Ticket creado con éxito',
            'ticket' => $response
        ], 201);
    }
}
