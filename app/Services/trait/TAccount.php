<?php

namespace App\Services\trait;

trait TAccount
{
    public function changeStatus(string $state): bool
    {
        return $this->getModel()->update(['estado' => $state]);
    }

    public function failureValidation(): bool
    {
        return $this->getModel()->decrement('intentos_validacion');
    }

}
