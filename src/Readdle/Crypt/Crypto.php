<?php
namespace Readdle\Crypt;

class Crypto implements CryptoInterface
{
    const CRYPTO_MARKER = '-CRYPT-';
    const BLOCK_SIZE = 16;

    /**
     * @param $value
     * @param $secret
     * @return string
     */
    public static function decrypt($value, $secret)
    {
        self::checkSecretLength($secret);
        
        if (strpos($value, self::CRYPTO_MARKER) !== 0) {
            return $value;
        }
        $value = substr($value, strlen(self::CRYPTO_MARKER));
        
        $iv = md5($secret, true);
        
        return openssl_decrypt(hex2bin($value), "AES-128-CBC", $secret, OPENSSL_RAW_DATA, $iv);
    }


    /**
     * @param $value
     * @param $secret
     * @return string
     */
    public static function encrypt($value, $secret)
    {
        self::checkSecretLength($secret);

        $iv = md5($secret, true);

        return self::CRYPTO_MARKER . bin2hex(
            openssl_encrypt($value, "AES-128-CBC", $secret,OPENSSL_RAW_DATA, $iv)
        );
    }

    /**
     * @param $secret
     */
    private static function checkSecretLength($secret)
    {
        if (strlen($secret) !== 16) {
            throw new InvalidArgumentException("Secret length should be exactly 16 characters long");
        }
    }
}
