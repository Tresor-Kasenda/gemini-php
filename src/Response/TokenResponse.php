<?php

declare(strict_types=1);

namespace Scott\GeminiPhp\Response;

use UnexpectedValueException;

class TokenResponse
{
    public function __construct(
        protected readonly int $totalTokens,
    ) {
        if ($totalTokens < 0) {
            throw new UnexpectedValueException('totalTokens cannot be negative');
        }
    }

    public static function fromArray(array $array): self
    {
        return new self($array['totalTokens']);
    }
}
