<?php

namespace Scott\GeminiPhp\Response;

use InvalidArgumentException;
use Scott\GeminiPhp\Contract\HasValidator;
use ValueError;

class ContentResponse
{
    use HasValidator;

    public function __construct(
        protected readonly array          $candidates,
        protected readonly PromptFeedback $promptFeedback,
    )
    {
        $this->arrayType($candidates, Candidate::class);
    }

    public static function fromArray(array $array): self
    {
        if (empty($array['promptFeedback']) || !is_array($array['promptFeedback'])) {
            throw new InvalidArgumentException('invalid promptFeedback');
        }

        $candidates = array_map(
            static fn(array $candidate): Candidate => Candidate::fromArray($candidate),
            $array['candidates'] ?? [],
        );

        $promptFeedback = PromptFeedback::fromArray($array['promptFeedback']);

        return new self($candidates, $promptFeedback);
    }

    public function text(): string
    {
        $parts = $this->parts();

        if (count($parts) > 1 || !$parts[0] instanceof TextPart) {
            throw new ValueError('');
        }

        return $parts[0]->text;
    }

    public function parts(): array
    {
        if (empty($this->candidates)) {
            throw new ValueError('');
        }

        if (count($this->candidates) > 1) {
            throw new ValueError('');
        }

        return $this->candidates[0]->content->parts;
    }
}
