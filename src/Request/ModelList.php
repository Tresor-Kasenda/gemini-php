<?php

declare(strict_types=1);

namespace Scott\GeminiPhp\Request;

use Scott\GeminiPhp\Contract\RequestContract;

class ModelList implements RequestContract
{
    public function getOperation(): string
    {
        return 'models';
    }

    public function getHttpMethod(): string
    {
        return 'GET';
    }

    public function getHttpPayload(): string
    {
        return '';
    }

}
