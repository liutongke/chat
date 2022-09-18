<?php

namespace App\Ext;

use Spatie\Crypto\Rsa\KeyPair;

class Rsa
{
    private static string $pathToPrivateKey = ROOT_PATH . 'storage/rsa/private.pem';
    private static string $pathToPublicKey = ROOT_PATH . 'storage/rsa/public.pem';

    public function __construct()
    {
        if (!file_exists(self::$pathToPrivateKey) || !file_exists(self::$pathToPublicKey)) {
            (new KeyPair())->generate(self::$pathToPrivateKey, self::$pathToPublicKey);
        }
    }

    //RSA加密
    public function encrypt(string $data): string
    {
        $privateKey = \Spatie\Crypto\Rsa\PrivateKey::fromFile(self::$pathToPrivateKey);
        $encryptedData = $privateKey->encrypt($data);
        return base64_encode($encryptedData);
    }

    //RSA解密
    public function decrypt(string $data): string
    {
        $publicKey = \Spatie\Crypto\Rsa\PublicKey::fromFile(self::$pathToPublicKey);
        $decryptedData = $publicKey->decrypt(base64_decode($data));
        return $decryptedData;
    }
}