<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\MiCorreo;

class EmailController extends Controller
{
    public function enviarCorreo()
    {
        $destinatario = 'd.velezposso@gmail.com';
        $mensaje = 'Este es un mensaje de prueba desde Laravel usando Gmail SMTP.';

        Mail::to($destinatario)->send(new MiCorreo($mensaje));

        return 'Correo enviado exitosamente';
    }
}
