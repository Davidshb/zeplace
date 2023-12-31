<?php

namespace App\Security;

use SensitiveParameter;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class TokenHasher implements PasswordHasherInterface
{
    /**
     * @inheritDoc
     */
    public function hash(#[SensitiveParameter] string $plainPassword): string
    {
        return hash('sha256', $plainPassword);
    }

    /**
     * @inheritDoc
     */
    public function verify(string $hashedPassword, #[SensitiveParameter] string $plainPassword): bool
    {
        return $this->hash($plainPassword) == $hashedPassword;
    }

    /**
     * @inheritDoc
     */
    public function needsRehash(string $hashedPassword): bool
    {
        return false;
    }
}
