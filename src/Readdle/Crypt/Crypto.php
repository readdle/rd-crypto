<?php

declare(strict_types = 1);

namespace Readdle\Crypt;

final class Crypto implements CryptoInterface
{
    private const CRYPTO_MARKER = "-CRYPT-V2-";
    private const CRYPT_METHOD  = "aes-256-gcm";
    private const BLOCK_SIZE    = 16;
    private $secret;
    
    public function __construct(Secret $secret)
    {
        $this->secret = $secret;
    }
    
    public function encrypt(string $value): string
    {
        $tag       = "";
        $iv        = \openssl_random_pseudo_bytes(self::BLOCK_SIZE);
        $encrypted = \openssl_encrypt($value, self::CRYPT_METHOD, (string)$this->secret, OPENSSL_RAW_DATA, $iv, $tag);
        
        return self::CRYPTO_MARKER . \bin2hex($iv) . \bin2hex($encrypted) . \bin2hex($tag);
    }
    
    public function decrypt(string $value): string
    {
        if (0 !== \strpos($value, self::CRYPTO_MARKER)) {
            return $value;
        }
        
        if (\strlen($value) < self::BLOCK_SIZE * 4) {
            return $value;
        }
        
        $original = $value;
        try {
            $value     = \substr($value, \strlen(self::CRYPTO_MARKER));
            $iv        = \substr($value, 0, self::BLOCK_SIZE * 2);
            $decrypted = \substr($value, self::BLOCK_SIZE * 2, -(self::BLOCK_SIZE * 2));
            $tag       = \substr($value, -(self::BLOCK_SIZE * 2));
            
            return \openssl_decrypt(
                \hex2bin($decrypted),
                self::CRYPT_METHOD,
                (string)$this->secret,
                OPENSSL_RAW_DATA,
                \hex2bin($iv),
                \hex2bin($tag)
            );
        } catch (\Throwable $e) {
            return $original;
        }
    }
}
