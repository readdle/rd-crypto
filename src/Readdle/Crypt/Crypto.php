<?php

declare(strict_types = 1);

namespace Readdle\Crypt;

final class Crypto implements CryptoInterface
{
    private const CRYPTO_MARKER = "-CRYPT-";
    private const CRYPT_METHOD  = "AES-128-CBC";
    private const BLOCK_SIZE    = 16;
    private $secret;
    
    public function __construct(Secret $secret)
    {
        $this->secret = $secret;
    }
    
    public function encrypt(string $value): string
    {
        $iv = openssl_random_pseudo_bytes(self::BLOCK_SIZE);
        $encrypted = bin2hex(openssl_encrypt($value, self::CRYPT_METHOD, (string)$this->secret, OPENSSL_RAW_DATA, $iv));
        
        return self::CRYPTO_MARKER . bin2hex($iv) . $encrypted;
    }
    
    public function decrypt(string $value): string
    {
        if (0 !== strpos($value, self::CRYPTO_MARKER)) {
            return $value;
        }
        
        $original = $value;
        try {
            $value = substr($value, strlen(self::CRYPTO_MARKER));
            $iv = hex2bin(substr($value, 0, self::BLOCK_SIZE * 2));
            $decrypted = hex2bin(substr($value, self::BLOCK_SIZE * 2));
            
            return openssl_decrypt($decrypted, self::CRYPT_METHOD, (string)$this->secret, OPENSSL_RAW_DATA, $iv);
        } catch (\Throwable $e) {
            return $original;
        }
    }
}
