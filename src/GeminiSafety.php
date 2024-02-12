<?php

namespace Scott\GeminiPhp;

class GeminiSafety
{
    public function __construct(
        public readonly HarmCategory       $category,
        public readonly HarmBlockThreshold $threshold,
    )
    {
    }

    /**
     * @return array{
     *     category: string,
     *     threshold: string,
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'category' => $this->category->value,
            'threshold' => $this->threshold->value,
        ];
    }

    public function __toString()
    {
        return json_encode($this) ?: '';
    }
}
