<?php

namespace App\Services;

use App\Models\ValidateAccount;
use App\Services\trait\TAccount;
use Illuminate\Support\Facades\Log;
use App\Models\AccountTicket;

class ValidateAccountService
{
    use TAccount;
    protected $Model;

    public function __construct($Model)
    {
        $this->Model = $Model;
    }

   
    public function getModel(): ValidateAccount
    {
        return $this->Model;
    }

    /**
     * Obtiene el tipo de ticket para una cuenta de validación.
     *
     * @return string
     */
    
}