<?php

declare(strict_types=1);

namespace App\Enum;

class IssueStatusEnum extends Enum
{
    public const NEW = 'new';
    public const ASSIGNED = 'assigned';
    public const OPEN = 'open';
    public const FIXED = 'fixed';

    protected static array $values = [
        self::NEW => 'New',
        self::ASSIGNED => 'Assigned',
        self::OPEN => 'Open',
        self::FIXED => 'Fixed',
    ];
}
