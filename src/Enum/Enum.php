<?php

declare(strict_types=1);

namespace App\Enum;

class Enum
{
    protected static array $values = [];

    public static function getAvailableKeys(): array
    {
        return array_keys(static::$values);
    }

    public static function getName(string $key): string
    {
        if (!isset(static::$values[$key])) {
            return "Unknown key (${$key})";
        }

        return static::$values[$key];
    }

    public static function getKey(string $name): string
    {
        if (!in_array($name, static::$values, true)) {
            return "Unknown name (${$name})";
        }

        return array_search($name, static::$values, true);
    }

    public static function getDefaultKey(): string
    {
        return array_keys(static::$values)[0];
    }

    public static function getAvailableNames(): array
    {
        return static::$values;
    }
}
