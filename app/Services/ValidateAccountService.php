<?php

namespace App\Services;

use App\Models\ValidateAccount;
use App\Services\trait\TAccount;

class ValidateAccountService
{
    use TAccount;
    protected $Model;

    public function __construct($Model)
    {
        $this->Model = $Model;
    }

    public function isContractor(): bool
    {
        return $this->getModel()->rol_asignado === ValidateAccount::CONTRACTOR;
    }

    public function getModel(): ValidateAccount
    {
        return $this->Model;
    }
}
