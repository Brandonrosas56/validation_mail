<?php

namespace App\Services;

use App\Models\CreateAccount;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class CreateAccountService
{
    protected $Model;

    public function __construct($Model) {
        $this->Model = $Model;
    }

    public function changeStatus($state) : bool {
        return $this->getModel()->update(['estado'=>$state]) ;
    }

    public function getModel() : CreateAccount {
        return $this->Model;
    }
}
