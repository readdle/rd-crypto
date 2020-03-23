<?php

declare(strict_types = 1);

namespace Readdle\Crypt;

use PHPUnit\Framework\TestCase;

class SecretTest extends TestCase
{
    public function testInvalidSecret()
    {
        $this->expectException(InvalidArgumentException::class);
        Secret::fromString("aaaaa");
    }
    
    public function testValidSecret()
    {
        $value = \str_pad("", Secret::MIN_LENGTH, "a");
        self::assertSame($value, (string)Secret::fromString($value));
    
        $value = \str_pad("", Secret::MIN_LENGTH * 2, "a");
        self::assertSame($value, (string)Secret::fromString($value));
    }
    
    public function testSaltedSecret()
    {
        $value  = \str_pad("", Secret::MIN_LENGTH, "a");
        $secret = Secret::fromString($value);
        $salty  = $secret->salty("");
        
        self::assertNotEquals(\spl_object_id($secret), \spl_object_id($salty));
        
        self::assertSame($value, (string)$secret);
        self::assertNotSame((string)$secret, (string)$salty);
    }
}
