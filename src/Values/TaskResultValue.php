<?php

namespace App\Values;

use Runn\ValueObjects\Values\EnumValue;

/**
 * Результат, с которым завершилась задача
 *
 * Class TaskResultValue
 * @package App\Values
 */
class TaskResultValue extends EnumValue
{
    public const VALUES = [
        'SUCCESS' => 'success',
        'FAIL' => 'fail',
        'ABORT' => 'abort',
    ];
}
