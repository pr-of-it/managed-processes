<?php

namespace App;

/**
 * Абстрактный класс "воркера" - рабочего процесса
 *
 * Class Worker
 * @package App
 */
abstract class Worker
{

    // Метод, который и будет выполнять полезную работу
    abstract protected function handle();

    // Метод "запуска" воркера
    final public function __invoke(): void
    {
        $this->handle();
    }

}
