<?php

declare(strict_types = 1);

namespace Readdle\Crypt\V1;

use PHPUnit\Framework\TestCase;
use Readdle\Crypt\Secret;

final class CryptoTest extends TestCase
{
    private string $secret;
    private Crypto $rdcrypto;
    
    public function invalidDecryptedValues(): array
    {
        return [
            ["-CRYPT-V2-qw215ea85900cf7f9e41a28facfaed8409209aa44b4e4eefb33b5a9929ea2e498"],
            ["-CRYPT-V2-1115ea85900cf7f9e41a28facfaed8409209aa44b4e4eefb33b5a9929ea2e498"],
            ["-FAKE-1115ea85900cf7f9e41a28facfaed8409209aa44b4e4eefb33b5a9929ea2e498"],
        ];
    }
    
    /** @dataProvider invalidDecryptedValues */
    public function testDecryptedInvalidValuesNotChanged(string $bad)
    {
        $result = $this->rdcrypto->decrypt($bad);
        $this->assertSame($bad, $result);
    }
    
    public function testSuccessFullEncodeDecode(): void
    {
        $encrypted = $this->rdcrypto->encrypt("value");
        $decrypted = $this->rdcrypto->decrypt($encrypted);
        self::assertEquals("value", $decrypted);
    }
    
    public function testCompatibilityWithJs(): void
    {
        $this->rdcrypto = new Crypto(Secret::fromString("NGYR4rBcywrVLqON"));
        $decrypted      = $this->rdcrypto->decrypt("-CRYPT-009aa9c7121aacbef2863d136d9de08f");
        self::assertEquals("value", $decrypted);
    }
    
    protected function setUp(): void
    {
        $this->secret   = "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";
        $this->rdcrypto = new Crypto(Secret::fromString($this->secret));
    }
}
