<?php

namespace Scott\GeminiPhp\Contract;

interface RequestContract
{
    public function getOperation(): string;

    public function getHttpMethod(): string;

    public function getHttpPayload(): string;
}
