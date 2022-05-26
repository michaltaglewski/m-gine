<?php

namespace mgine\base;

use \mgine\base\StringHelper;

class Security extends Component
{
    public function generateRandomKey(int $length = 32): string
    {
        return random_bytes($length);
    }

    public function generateRandomString($length = 32)
    {
        $bytes = $this->generateRandomKey($length);

        return substr(StringHelper::base64UrlEncode($bytes), 0, $length);
    }

    public function generateRandomHashString(): string
    {
        return md5(uniqid(rand(), TRUE));
    }
}