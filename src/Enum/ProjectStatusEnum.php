<?php

declare(strict_types=1);

namespace App\Enum;

class ProjectStatusEnum extends Enum
{
    public const ACTIVE = 'active';
    public const COMPLETED = 'completed';
    public const ARCHIVED = 'archived';

    protected static array $values = [
        self::ACTIVE => 'Active',
        self::COMPLETED => 'Completed',
        self::ARCHIVED => 'Archived',
    ];
}
