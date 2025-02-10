<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'user_blocked',
        'primer_nombre',     // Asegúrate de agregar estos campos si no están.
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Método para generar las opciones de correo basadas en los datos del usuario.
     *
     * @return array
     */
    public function generarCorreos($primer_nombre, $segundo_nombre, $primer_apellido, $segundo_apellido) {
        $dominio = '@sena.edu.co';
    
        $primer_nombre = strtolower($primer_nombre);
        $segundo_nombre = strtolower($segundo_nombre ?? '');
        $primer_apellido = strtolower($primer_apellido);
        $segundo_apellido = strtolower($segundo_apellido ?? '');
    
        $opcion1 = substr($primer_nombre, 0, 1) . $primer_apellido . $dominio;  
        $opcion2 = substr($primer_nombre, 0, 1) . $primer_apellido . substr($segundo_apellido, 0, 1) . $dominio;  
        $opcion3 = substr($primer_nombre, 0, 1) . substr($segundo_nombre, 0, 1) . $primer_apellido . $dominio;  
        $opcion4 = substr($primer_nombre, 0, 1) . substr($segundo_nombre, 0, 1) . $primer_apellido . substr($segundo_apellido, 0, 1) . $dominio;  
    
        return [
            'opcion1' => $opcion1,
            'opcion2' => $opcion2,
            'opcion3' => $opcion3,
            'opcion4' => $opcion4,
        ];
    }

    /**
     * Método para generar los correos masivos con paginación.
     *
     * @return \Illuminate\Support\Collection
     * @return \Illuminate\Pagination\LengthAwarePaginator

     */
    public function generarCorreosMasivosPaginados()
{
    // Usar paginación para obtener los usuarios de 100 en 100
    $usuariosPaginados = User::paginate(100);
    
    // Aplicar la función de generación de correos a los usuarios de la página actual
    $usuariosPaginados->getCollection()->transform(function($usuario) {
        return $this->generarCorreos(
            $usuario->primer_nombre, 
            $usuario->segundo_nombre, 
            $usuario->primer_apellido, 
            $usuario->segundo_apellido
        );
    });

    return $usuariosPaginados;
}

    /**
     * Define eventos de modelo para registrar auditorías en la tabla 'audits' cuando se crean o actualizan usuarios.
     */
    protected static function booted()
    {
        static::created(function (User $user) {
            Audit::create([
                'user_id' => $user->id, 
                'author' =>  Auth::user()->name ?? 'System',
                'event' => 'Creado',
                'previous_state' => 'Se creó el usuario con id '. $user->id ,
                'new_state' => '',
                'table'=> 'users',
            ]);
        });

        static::updated(function (User $user) {
            $changes = $user->getChanges();
            $original = $user->getOriginal();

            foreach ($changes as $attribute => $newValue) {
                if ($attribute == 'updated_at' || !isset($original[$attribute])) {
                    continue;
                }

                $oldValue = $original[$attribute];

                Audit::create([
                    'user_id' => $user->id,
                    'author' => Auth::user()->name ?? 'System',
                    'event' => 'Actualizado',
                    'previous_state' => "{$attribute}: {$oldValue}",
                    'new_state' => "{$attribute}: {$newValue}",
                    'table' => 'users',
                    'created_at' => $user->created_at,
                    'updated_at' => now(),
                ]);
            }
        });
    }
}

