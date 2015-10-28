<?php
namespace Readdle\Crypt;

interface CryptoInterface
{
    public static function encrypt($encrypt, $secret);

    public static function decrypt($encrypt, $secret);
}