<?php

namespace App\Domain\Shared\Validator;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class PolishNumber extends Constraint
{
    public const TYPE_PESEL = 'PESEL';
    public const TYPE_NIP = 'NIP';
    public const TYPE_REGON = 'REGON';
    public const TYPE_ID = 'ID';

    public string $message = 'The "{{ number }}" is not a valid "{{ type }}".';
    public bool $strict = true;
    public string $type;

    #[HasNamedArguments]
    public function __construct(string $type, ?array $groups = null, mixed $payload = null)
    {
        parent::__construct([], $groups, $payload);

        $this->type = $type;
    }
}
