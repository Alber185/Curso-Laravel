<?php

namespace App\Business\Services;

use Exception;
use Illuminate\Support\Facades\Crypt;

class EncryptService
{
    private string $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function encrypt(string $data): string
    {
        return base64_encode($this->key.":".Crypt::encryptString($data));
    }

    public function decrypt(string $data): string
    {
        $decodeData = base64_decode($data);

        [$key, $encrypted] = explode(":", $decodeData, 2);

        if ($key !== $this->key) {
            throw new Exception("Clave incorrecta");
        }

        return Crypt::decryptString($encrypted);
    }
}