<?php

namespace App\Business\Services;

use App\Business\Interfaces\MessageServiceInterface;

class MessageService implements MessageServiceInterface
{
    public function hi(): string
    {
        return "Hola mundo";
    }
}