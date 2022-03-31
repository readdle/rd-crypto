<?php

declare(strict_types = 1);

namespace Readdle\Crypt\V2;

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
    public function testDecryptedInvalidValuesNotChanged(string $bad): void
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
        $this->rdcrypto = new Crypto(Secret::fromString("NGYR4rBcywrVLqON")->salty("08c51bfa2b2a4812b1a8582a"));
        $decrypted      = $this->rdcrypto->decrypt("-CRYPT-V2-1deedb92c859bb0f9153efc11130b0dcd83bc4e11452baecc0fe48fbb3d3696b45327ce447");
        self::assertEquals("value", $decrypted);
    }
    
    public function testPreviouslyEncrypted(): void
    {
        $decrypted = $this->rdcrypto->decrypt("-CRYPT-V2-227effcfccf3115e1b7c4725426efb0f66d1dcd58864863faeecca884dfa90f7e9b8f977cc");
        self::assertEquals("value", $decrypted);
    }
    
    protected function setUp(): void
    {
        $this->secret   = "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";
        $this->rdcrypto = new Crypto(Secret::fromString($this->secret));
    }
}
