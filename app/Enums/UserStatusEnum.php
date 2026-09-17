<?php

namespace App\Enums;

class UserStatusEnum
{
    const ACTIVE = true;
    const INACTIVE = false;

    /**
     * Get all status values
     *
     * @return array
     */
    public static function getValues(): array
    {
        return [
            self::ACTIVE => 'Hoạt động',
            self::INACTIVE => 'Không hoạt động',
        ];
    }

    /**
     * Get status label
     *
     * @param bool $status
     * @return string
     */
    public static function getLabel(bool $status): string
    {
        return self::getValues()[$status] ?? 'Unknown';
    }
}