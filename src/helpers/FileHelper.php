<?php

namespace mgine\helpers;

use OpenSSLAsymmetricKey;

/**
 * FileHelper
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
class FileHelper extends \mgine\base\FileHelper
{
    /**
     * @param string $filename
     * @return OpenSSLAsymmetricKey|false
     * @throws \Exception
     */
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