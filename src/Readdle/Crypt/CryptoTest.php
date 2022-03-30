<?php

declare(strict_types = 1);

namespace Readdle\Crypt;

use PHPUnit\Framework\TestCase;
use Readdle\Crypt\V1\Crypto;

final class CryptoTest extends TestCase
{
    private string $secret;
    private Crypto $rdcrypto;
    
    public function testSuccessFullEncodeDecode(): void
    {
        $encrypted = $this->rdcrypto->encrypt("value");
        $decrypted = $this->rdcrypto->decrypt($encrypted);
        self::assertEquals("value", $decrypted);
    }
    
    public function testFallback(): void
    {
        $this->rdcrypto = new Crypto(Secret::fromString("NGYR4rBcywrVLqON"));
        $decrypted      = $this->rdcrypto->decrypt("-CRYPT-009aa9c7121aacbef2863d136d9de08f"); // encrypted with v1
        self::assertEquals("value", $decrypted);
    }
    
    protected function setUp(): void
    {
        $this->secret   = "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";
        $this->rdcrypto = new Crypto(Secret::fromString($this->secret));
    }
}
