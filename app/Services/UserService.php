<?php

namespace App\Services;

use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class UserService
{
    protected $Model;

    public function __construct($Model) {
        $this->Model = $Model;
    }

    public function isContractor() : bool {
        return $this->getModel()->functionary === User::CONTRACTOR;
    }

    public function getModel() : User {
        return $this->Model;
    }
}
