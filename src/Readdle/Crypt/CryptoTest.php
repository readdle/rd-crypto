<?php

declare(strict_types = 1);

namespace Readdle\Crypt;

use PHPUnit\Framework\TestCase;

class CryptoTest extends TestCase
{
    private $secret;
    private $rdcrypto;
    
    public function invalidDecryptedValues(): array
    {
        return [
            ["-CRYPT-qw215ea85900cf7f9e41a28facfaed8409209aa44b4e4eefb33b5a9929ea2e498"],
            ["-CRYPT-1115ea85900cf7f9e41a28facfaed8409209aa44b4e4eefb33b5a9929ea2e498"],
            ["-FAKE-1115ea85900cf7f9e41a28facfaed8409209aa44b4e4eefb33b5a9929ea2e498"],
        ];
    }
    
    /** @dataProvider invalidDecryptedValues */
    public function testDecryptedInvalidValuesNotChanged(string $bad)
    {
        $result = $this->rdcrypto->decrypt($bad);
        $this->assertSame($bad, $result);
    }
    
    public function testSuccessFullEncodeDecode()
    {
        $encrypted = $this->rdcrypto->encrypt("value");
        $decrypted = $this->rdcrypto->decrypt($encrypted);
        self::assertEquals("value", $decrypted);
    }
    
    protected function setUp(): void
    {
        $this->secret = "NGYR4rBcywrVLqON";
        $this->rdcrypto = new Crypto(Secret::fromString($this->secret));
    }
}
