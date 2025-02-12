<?php

namespace App\Http\Controllers;

use App\Services\GLPIService;

class TicketController extends Controller
{
    protected $glpiService;

    public function __construct(GLPIService $glpiService)
    {
        $this->glpiService = $glpiService;
    }

    public function createTicket()
    {
        $title = 'Problema en servidor';
        $content = 'Descripción detallada del problema';

        $response = $this->glpiService->createTicket($title, $content);

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 500);
        }

        return response()->json($response);
    }
}
