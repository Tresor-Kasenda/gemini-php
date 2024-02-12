<?php

declare(strict_types=1);

namespace Scott\GeminiPhp\Request;

use Scott\GeminiPhp\Contract\HasValidator;
use Scott\GeminiPhp\Contract\RequestContract;
use Scott\GeminiPhp\Enum\GeminiModel;
use Scott\GeminiPhp\GeminiConfig;

class GenerateData implements RequestContract
{
    use HasValidator;

    public function __construct(
        protected readonly GeminiModel   $geminiModel,
        protected readonly array         $contents,
        protected readonly array         $safetySettings = [],
        protected readonly ?GeminiConfig $generationConfig = null,
    )
    {
        $this->arrayType($this->contents, Content::class);
        $this->arrayType($this->safetySettings, SafetySetting::class);
    }

    public function getOperation(): string
    {
        return "{$this->geminiModel->value}:generateContent";
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
        $arr = [
            'model' => $this->geminiModel->value,
            'contents' => $this->contents,
        ];

        if (!empty($this->safetySettings)) {
            $arr['safetySettings'] = $this->safetySettings;
        }

        if ($this->generationConfig) {
            $arr['generationConfig'] = $this->generationConfig;
        }

        return $arr;
    }

    public function __toString(): string
    {
        return json_encode($this) ?: '';
    }
}
