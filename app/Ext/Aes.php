<?php

namespace App\Ext;

class Aes
{

    public static function generate(int $length = 24): string
    {
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz!@#$%^&*()_+-=?><,.":{}|[]';

        $len = strlen($str) - 1;
        $key = '';

        for ($i = 0; $i < $length; $i++) {
            $key .= $str[mt_rand(0, $len)];
        }

        return $key;
    }

    /**
     * AES解密
     * @param $content
     * @param $key
     * @return false|string
     */
    public function aesDecrypt($content, $key, $iv = 'ZZWBKJ_ZHIHUAWEI'): string
    {
        var_dump($content, $key);
        return openssl_decrypt(base64_decode($content), 'AES-256-CBC', $key, OPENSSL_ZERO_PADDING, $iv);
    }

    /**
     * AES加密
     * @param $content
     * @param $key
     * @return false|string
     */
    public function aesEncrypt($content, $key, $iv = 'ZZWBKJ_ZHIHUAWEI'): string
    {
        return base64_encode(openssl_encrypt($content, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv));
    }
}