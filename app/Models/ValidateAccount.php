<?php

namespace App\Models;

use App\Services\ValidateAccountService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidateAccount extends Model
{
    use HasFactory;
    const CONTRACTOR = 'Contratista';
    protected $table = 'validate_account';

    // Estados posibles
    public const STATE_PENDING = 'PENDING';
    public const STATE_VALIDATING = 'VALIDATING';
    public const STATE_VALIDATED = 'VALIDATED';
    public const STATE_REJECTED = 'REJECTED';
    public const STATE_ERROR = 'ERROR';

    // Estados de la cuenta
    public const ESTADO_EXITOSO = 'exitoso';
    public const ESTADO_RECHAZADO = 'rechazado';
    public const ESTADO_EN_REVISION = 'en_revision';

    /**
     * Los atributos que pueden asignarse masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rgn_id',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'documento_proveedor',
        'tipo_documento',
        'correo_personal',
        'correo_institucional',
        'numero_contrato',
        'fecha_inicio_contrato',
        'fecha_terminacion_contrato',
        'rol_asignado',
        'usuario',
        'user_id',
        'state',
        'estado'
    ];

    /**
     * Los atributos que deben ser convertidos.
     *
     * @var array<int, string>
     */
    protected $casts = [
        'fecha_inicio_contrato' => 'date',
        'fecha_terminacion_contrato' => 'date',
    ];

    public function regional()
    {
        return $this->belongsTo(Regional::class, 'rgn_id', 'rgn_id');
    }

    public function getService(): ValidateAccountService
    {
        return new ValidateAccountService($this);
    }

    /**
     * Obtiene el estado actual de la cuenta.
     *
     * @return string
     */
    public function getState(): string
    {
        return $this->state ?? self::STATE_PENDING;
    }

    /**
     * Verifica si la cuenta está en un estado específico.
     *
     * @param string $state
     * @return bool
     */
    public function isState(string $state): bool
    {
        return $this->getState() === $state;
    }

    /**
     * Verifica si la cuenta está pendiente.
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->isState(self::STATE_PENDING);
    }

    /**
     * Verifica si la cuenta está en validación.
     *
     * @return bool
     */
    public function isValidating(): bool
    {
        return $this->isState(self::STATE_VALIDATING);
    }

    /**
     * Verifica si la cuenta está validada.
     *
     * @return bool
     */
    public function isValidated(): bool
    {
        return $this->isState(self::STATE_VALIDATED);
    }

    /**
     * Verifica si la cuenta está rechazada.
     *
     * @return bool
     */
    public function isRejected(): bool
    {
        return $this->isState(self::STATE_REJECTED);
    }

    /**
     * Verifica si la cuenta está en error.
     *
     * @return bool
     */
    public function isError(): bool
    {
        return $this->isState(self::STATE_ERROR);
    }

    /**
     * Obtiene los tickets asociados a la cuenta.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(AccountTicket::class, 'account_id');
    }
}
