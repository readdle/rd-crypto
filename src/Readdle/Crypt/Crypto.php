<?php

declare(strict_types = 1);

namespace Readdle\Crypt;

final class Crypto implements CryptoInterface
{
    private V2\Crypto $main;
    private V1\Crypto $fallback;
    
    public function __construct(Secret $secret)
    {
        $this->main     = new \Readdle\Crypt\V2\Crypto($secret);
        $this->fallback = new \Readdle\Crypt\V1\Crypto($secret);
    }
    
    public function encrypt(string $value): string
    {
        return $this->main->encrypt($value);
    }
    
    public function decrypt(string $value): string
    {
        return $this->fallback->decrypt(
            $this->main->decrypt($value)
        );
    }
}
