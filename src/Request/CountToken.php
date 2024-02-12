<?php

namespace Scott\GeminiPhp\Request;

use Scott\GeminiPhp\Contract\HasValidator;
use Scott\GeminiPhp\Contract\RequestContract;
use Scott\GeminiPhp\Enum\GeminiModel;

class CountToken implements RequestContract
{
    use HasValidator;

    public function __construct(
        public readonly GeminiModel $geminiModel,
        public readonly array       $contents,
    )
    {
        $this->arrayType($this->contents, Content::class);
    }

    public function getOperation(): string
    {
        return "{$this->geminiModel->value}:countTokens";
    }

    public function getHttpMethod(): string
    {
        return 'POST';
    }

    public function getHttpPayload(): string
    {
        return (string)$this;
    }

    public function jsonSerialize(): array
    {
        return [
            'model' => $this->geminiModel->value,
            'contents' => $this->contents,
        ];
    }

    public function __toString(): string
    {
        return json_encode($this) ?: '';
    }
}
