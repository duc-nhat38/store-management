<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static UNAVAILABLE()
 * @method static static AVAILABLE()
 */
final class ProductStatus extends Enum
{
    const UNAVAILABLE = 0;
    const AVAILABLE = 1;
}
