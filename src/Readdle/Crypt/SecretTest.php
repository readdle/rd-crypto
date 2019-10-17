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
        $value = str_pad("", Secret::LENGTH, "a");
        self::assertSame($value, (string)Secret::fromString($value));
    }
}
