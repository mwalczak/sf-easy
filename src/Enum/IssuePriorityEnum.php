<?php

declare(strict_types=1);

namespace App\Enum;

class IssuePriorityEnum extends Enum
{
    public const BLOCKER = 'blocker';
    public const HIGH = 'high';
    public const MEDIUM = 'medium';
    public const LOW = 'low';

    protected static array $values = [
        self::BLOCKER => 'Blocker',
        self::HIGH => 'High',
        self::MEDIUM => 'Medium',
        self::LOW => 'Low',
    ];
}
