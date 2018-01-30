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
    abstract protected function handle(): \Generator;

    // Метод "запуска" воркера
    final public function __invoke(): void
    {
        foreach ($this->handle() as $done) {
            // Здесь будет находиться точка прерывания воркера
            echo $done . '...';
        };
        echo "\n";
    }

}
