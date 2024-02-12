<?php

declare(strict_types=1);

namespace Scott\GeminiPhp\Contract;

use InvalidArgumentException;

trait HasValidator
{
    protected function arrayString(array $items): void
    {
        foreach ($items as $item) {
            if (!is_string($item)) {
                throw new InvalidArgumentException(
                    sprintf('Expected string but found %s', is_object($item) ? $item::class : gettype($item)),
                );
            }
        }
    }

    private function arrayType(array $items, string $classString): void
    {
        foreach ($items as $item) {
            if (!$item instanceof $classString) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Expected type %s but found %s',
                        $classString,
                        is_object($item) ? $item::class : gettype($item),
                    ),
                );
            }
        }
    }
}
