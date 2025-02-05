<?php

/* namespace App\Listeners;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Events\EloquentEvent;
use App\Models\Audit;

class EloquentEventListener
{
    public function handle($event)
    {
        if ($event instanceof EloquentEvent) {
            $model = $event->getModel();
            $userId = null;
            $userName = null;

            // Obtener el usuario autenticado
            $user = Auth::user();

            if ($user) {
                // Si hay un usuario autenticado, obtener su ID y nombre
                $userId = $user->id;
                $userName = $user->name;
            }

            $author = Request::ip(); // O cualquier otra forma de identificar al autor de la acción

            if ($event->getName() === 'eloquent.created') {
                Audit::create([
                    'user_id' => $userId,
                    'author' => $userName,
                    'event' => 'created',
                    'new_state' => $model->toJson(),
                ]);
            } elseif ($event->getName() === 'eloquent.updated') {
                Audit::create([
                    'user_id' => $userId,
                    'author' => $userName,
                    'event' => 'updated',
                    'previous_state' => $model->getOriginal(),
                    'new_state' => $model->toJson(),
                ]);
            } elseif ($event->getName() === 'eloquent.deleted') {
                Audit::create([
                    'user_id' => $userId,
                    'author' => $userName,
                    'event' => 'deleted',
                    'previous_state' => $model->toJson(),
                ]);
            }
        }
    }
} */