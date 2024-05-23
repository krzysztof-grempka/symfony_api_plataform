<?php

namespace App\Application\Shared\Exception;

use Symfony\Component\HttpFoundation\Response;

final class UniqueConstraintException extends \Exception
{
    public const MESSAGE = 'Duplicated entry';

    public function __construct(string $message = self::MESSAGE)
    {
        parent::__construct($message, Response::HTTP_CONFLICT);
    }
}
