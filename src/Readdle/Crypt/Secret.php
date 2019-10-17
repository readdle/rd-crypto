<?php

declare(strict_types = 1);

namespace Readdle\Crypt;

final class Secret
{
    const LENGTH = 16;
    
    private $value;
    
    private function __construct(string $value)
    {
        if (self::LENGTH !== strlen($value)) {
            throw new InvalidArgumentException(
                sprintf(
                    "Secret length should be exactly %s characters long",
                    self::LENGTH
                )
            );
        }
        $this->value = $value;
    }
    
    public static function fromString(string $value): self
    {
        return new self($value);
    }
    
    public function __toString(): string
    {
        return $this->value;
    }
}
