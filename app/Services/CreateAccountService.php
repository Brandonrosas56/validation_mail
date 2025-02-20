<?php

namespace App\Services;

use App\Models\CreateAccount;
use App\Services\trait\TAccount;

class CreateAccountService
{
    use TAccount;
    protected $Model;

    public function __construct($Model)
    {
        $this->Model = $Model;
    }

    public function getModel(): CreateAccount
    {
        return $this->Model;
    }
}
