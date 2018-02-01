<?php

namespace App\Values;

use Runn\ValueObjects\Values\IntValue;

/**
 * "Полётное задание" для тестового воркера
 * Представляет из себя просто число повторений его пустого цикла
 *
 * Class TestworkerExecuteValue
 * @package App\Values
 */
class TestWorkerExecuteValue extends IntValue implements TaskExecuteValue
{
}
