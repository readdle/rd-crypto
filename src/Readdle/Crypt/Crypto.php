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
        if (strpos($value, self::CRYPTO_MARKER) !== 0) {
            return $value;
        }

        self::checkSecretLength($secret);

        $value = substr($value, strlen(self::CRYPTO_MARKER));
        $iv = md5($secret, true);

        return self::unpad(
            mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $secret, hex2bin($value), MCRYPT_MODE_CBC, $iv)
        );
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

        //don't use default php padding which is '\0'
        $pad = self::BLOCK_SIZE - (strlen($value) % self::BLOCK_SIZE);
        $data = $value . str_repeat(chr($pad), $pad);

        return self::CRYPTO_MARKER . bin2hex(
            mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $secret, $data, MCRYPT_MODE_CBC, $iv)
        );
    }

    /**
     * @param $str
     * @return string
     */
    private static function unpad($str)
    {
        $len = strlen($str);
        $pad = ord($str[$len - 1]);
        if ($pad && $pad <= self::BLOCK_SIZE) {
            if (substr($str, -$pad) === str_repeat(chr($pad), $pad)) {
                return substr($str, 0, $len - $pad);
            }
        }
        return $str;
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