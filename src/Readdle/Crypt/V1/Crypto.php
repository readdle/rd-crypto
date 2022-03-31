<?php

declare(strict_types=1);

namespace Readdle\Crypt\V1;

use Readdle\Crypt\CryptoInterface;
use Readdle\Crypt\Secret;

final class Crypto implements CryptoInterface
{
    private const CRYPTO_MARKER = '-CRYPT-';
    private Secret $secret;
    
    public function __construct(Secret $secret)
    {
        $this->secret = $secret;
    }
    
    public function encrypt(string $value): string
    {
        $iv        = \md5((string)$this->secret, true);
        $encrypted = \openssl_encrypt($value, "AES-128-CBC", (string)$this->secret, OPENSSL_RAW_DATA, $iv);
        
        return self::CRYPTO_MARKER . \bin2hex($encrypted);
    }

    public function decrypt(string $value): string
    {
        if (0 !== \strpos($value, self::CRYPTO_MARKER)) {
            return $value;
        }
        
        $original = $value;
        
        try {
            $value = \substr($value, \strlen(self::CRYPTO_MARKER));
            $iv    = \md5((string)$this->secret, true);
            
            return \openssl_decrypt(
                \hex2bin($value),
                "AES-128-CBC",
                (string)$this->secret,
                OPENSSL_RAW_DATA,
                $iv
            );
        } catch (\Throwable $e) {
            return $original;
        }
    }
}
