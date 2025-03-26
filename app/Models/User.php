<?php

namespace App\Models;

use App\Services\UserService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable, HasRoles;
    
    protected $with = ['roles']; // Carga siempre los roles asociados al usuario
    protected $guard_name = 'web'; // Especifica el guard para la autenticación con Spatie
    
    /**
     * Define la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Atributos que pueden asignarse masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'supplier_document',
        'position',
        'email',
        'password',
        'rgn_id',
        'registrar_id',
        'lock',
    ];

    /**
     * Atributos que deben ocultarse en la serialización.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * Atributos adicionales agregados al modelo.
     *
     * @var array<int, string>
     */
    protected $appends = ['profile_photo_url'];

    /**
     * Define los atributos que deben ser convertidos a un tipo de dato específico.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación con la tabla de regionales.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function regional()
    {
        return $this->belongsTo(Regional::class, 'rgn_id', 'rgn_id');
    }

    /**
     * Obtiene el servicio de usuario asociado.
     *
     * @return UserService
     */
    function getService(): UserService
    {
        return new UserService($this);
    }
}
