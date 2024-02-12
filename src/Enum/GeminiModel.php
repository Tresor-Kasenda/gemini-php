<?php

declare(strict_types=1);

namespace Scott\GeminiPhp\Enum;

enum GeminiModel: string
{
    case DEFAULT = 'models/text-bison-001';

    case PRO_VISION = 'models/gemini-pro';

    case PRO = 'models/gemini-pro-vision';

    case EMBED = 'models/embedding-001';
}
