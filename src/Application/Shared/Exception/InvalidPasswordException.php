<?php

namespace App\Application\Shared\Exception;

class InvalidPasswordException extends \RuntimeException
{
    public function __construct(string $message = 'Invalid password.', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
