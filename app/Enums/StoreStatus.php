<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static CLOSED()
 * @method static static TEMPORARILY_STOPPED_OPERATING()
 * @method static static ACTIVE()
 */
final class StoreStatus extends Enum
{
    const CLOSED = 0;
    const TEMPORARILY_STOPPED_OPERATING= 1;
    const ACTIVE = 2;
}
