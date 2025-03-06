<?php

namespace App\Models;

use App\Services\UserService;
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
    
    protected $with = ['roles']; // Esto carga siempre los roles

    // Si ya tienes el guard especificado en config/permission.php, esto no es necesario:
    protected $guard_name = 'web';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';

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
     * The attributes that should be hidden for serialization.
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
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
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
    public function regional()
    {
        return $this->belongsTo(Regional::class, 'rgn_id', 'rgn_id');
    }

    function getService() : UserService {
        return new UserService($this);
    }

    /**
     * Define eventos de modelo para registrar auditorías en la tabla 'audits' cuando se crean o actualizan usuarios.
     */
    // protected static function booted()
    // {
    //     static::created(function (User $user) {
            
    //         Audit::create([
    //             'user_id' => $user->id, 
    //             'author' =>  Auth::user()->name ?? 'System',
    //             'event' => 'Creado',
    //             'previous_state' => 'Se creó le usuario con id '. $user->id ,
    //             'new_state' => '',
    //             'table'=> 'users',
                
    //         ]);
    //     });

    //     static::updated(function (User $user) {
        
    //         $changes = $user->getChanges();
    //         $original = $user->getOriginal();

    //         foreach ($changes as $attribute => $newValue) {
    //             if ($attribute == 'updated_at' || !isset($original[$attribute])) {
    //                 continue;
    //             }

    //             $oldValue = $original[$attribute];

    //             Audit::create([
    //                 'user_id' => $user->id,
    //                 'author' => Auth::user()->name ?? 'System',
    //                 'event' => 'Actualizado',
    //                 'previous_state' => "{$attribute}: {$oldValue}",
    //                 'new_state' => "{$attribute}: {$newValue}",
    //                 'table' => 'users',
    //                 'created_at' => $user->created_at,
    //                 'updated_at' => now(),
    //             ]);
    //         }
    //     });
    //}




}