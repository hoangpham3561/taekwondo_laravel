<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class CategoryStatusEnum extends Enum
{
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const DELETED = 'deleted';
}
