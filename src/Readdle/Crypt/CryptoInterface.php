<?php

namespace Readdle\Crypt;

interface CryptoInterface
{
    public function encrypt(string $value): string;

    public function decrypt(string $value): string;
}
