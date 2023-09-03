<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static STORE()
 * @method static static PRODUCT()
 */
final class FolderName extends Enum
{
    const STORE = 'stores';
    const PRODUCT = 'products';
}
