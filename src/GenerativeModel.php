<?php

declare(strict_types=1);

namespace Scott\GeminiPhp;

use Scott\GeminiPhp\Contract\HasValidator;
use Scott\GeminiPhp\Enum\GeminiModel;
use Scott\GeminiPhp\Request\CountToken;
use Scott\GeminiPhp\Request\GenerateData;
use Scott\GeminiPhp\Response\ContentResponse;
use Scott\GeminiPhp\Response\TokenResponse;

class GenerativeModel
{
    use HasValidator;

    private array $safetySettings = [];

    private ?GeminiConfig $generationConfig = null;

    public function __construct(
        protected readonly Gemini      $geminiClient,
        protected readonly GeminiModel $geminiModel,
    )
    {
    }

    public function generateContent(...$parts): ContentResponse
    {
        $content = new Content($parts, 'user');

        return $this->generateContentWithContents([$content]);
    }

    public function generateContentWithContents(array $contents): ContentResponse
    {
        $this->arrayType($contents, Content::class);

        $request = new GenerateData(
            $this->geminiModel,
            $contents,
            $this->safetySettings,
            $this->generationConfig,
        );

        return $this->geminiClient->generateContent($request);
    }

    public function startChat(): ChatSession
    {
        return new ChatSession($this);
    }

    public function countTokens(...$parts): TokenResponse
    {
        $content = new Content($parts, 'user');
        $request = new CountToken(
            $this->geminiModel,
            [$content],
        );

        return $this->geminiClient->countTokens($request);
    }

    public function withAddedSafetySetting(SafetySetting $safetySetting): self
    {
        $clone = clone $this;
        $clone->safetySettings[] = $safetySetting;

        return $clone;
    }

    public function withGenerationConfig(GeminiConfig $generationConfig): self
    {
        $clone = clone $this;
        $clone->generationConfig = $generationConfig;

        return $clone;
    }
}
