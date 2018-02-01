<?php

namespace App\Entities;

use Runn\Core\TypedCollection;

/**
 * Типизированная коллекция задач
 *
 * Class Tasks
 * @package App\Entities
 */
class Tasks extends TypedCollection
{
    public static function getType()
    {
        return Task::class;
    }
}
