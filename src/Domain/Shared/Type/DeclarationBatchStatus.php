<?php

declare(strict_types=1);

namespace App\Domain\Shared\Type;

enum DeclarationBatchStatus: string
{
    case Init = 'init';

    case Processing = 'processing';

    case Done = 'done';

    case Error = 'error';
}
