<?php

namespace mgine\helpers;

use OpenSSLAsymmetricKey;

class FileHelper
{

    public static function readPublicKeyFile(string $filename): OpenSSLAsymmetricKey|false
    {
        if(!file_exists($filename)){
            throw new \Exception('Public key file not found.');
        }

        $fp = @fopen($filename,'r');

        $pkey = openssl_pkey_get_public(fread($fp, 8192)); // file_get_contents($filename)

        fclose($fp);

        return $pkey;
    }

}