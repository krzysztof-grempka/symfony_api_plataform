<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\ApiFilter;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class ApiFilter
{
    public const GREATER_THAN = 'gt';
    public const LESS_THAN = 'lt';
    public const GREATER_THAN_OR_EQUAL = 'gte';
    public const LESS_THAN_OR_EQUAL = 'lte';
    public const PARTIAL = 'partial';
    public const STARTS = 'starts';
    public const IGNORE_ACCENT = 'accent';

    public const ENABLED_ARRAY_OPERATORS = [
        self::GREATER_THAN,
        self::LESS_THAN,
        self::GREATER_THAN_OR_EQUAL,
        self::LESS_THAN_OR_EQUAL,
        self::PARTIAL,
        self::STARTS,
        self::IGNORE_ACCENT,
    ];

    public const SQL_OPERATOR_SIGN_MAP = [
        self::GREATER_THAN => '>',
        self::LESS_THAN => '<',
        self::GREATER_THAN_OR_EQUAL => '>=',
        self::LESS_THAN_OR_EQUAL => '<=',
    ];

    public const TEMPLATE_STRING = '{{value}}';

    public const SQL_OPERATOR_TEMPLATE_MAP = [
        self::PARTIAL => '%'.self::TEMPLATE_STRING.'%',
        self::STARTS => self::TEMPLATE_STRING.'%',
    ];

    public function __construct(
        public bool $required = false,
        public bool $allowTemplates = false,
        public array $properties = [],
        public string $subresource = '',
        public string $customFilter = '',
    ) {
    }
}
