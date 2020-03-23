<?php

declare(strict_types = 1);

namespace Readdle\Crypt;

final class Secret
{
    const MIN_LENGTH = 16;
    
    private $value;
    
    private function __construct(string $value)
    {
        if (self::MIN_LENGTH > \strlen($value)) {
            throw new InvalidArgumentException(
                \sprintf("Secret length should be greater than %s characters", self::MIN_LENGTH)
            );
        }
        $this->value = $value;
    }
    
    public static function fromString(string $value): self
    {
        return new self($value);
    }
    
    public function salty(string $salt): self
    {
        return new self(\hash_pbkdf2("sha3-256", $this->value, $salt, 100, 32, true));
    }
    
    public function __toString(): string
    {
        return $this->value;
    }
}
