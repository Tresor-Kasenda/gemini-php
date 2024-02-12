<?php

namespace Scott\GeminiPhp\Response;

class ModelResponse
{
    public function __construct(
        protected readonly array $models,
    )
    {
    }

    public static function fromArray(array $json): self
    {
        $models = array_map(
            static fn(array $arr): Model => Model::fromArray($arr),
            $json['models'],
        );

        return new self($models);
    }
}
