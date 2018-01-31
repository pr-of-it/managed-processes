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

    // Здесь хранится PID текущего процесса
    protected $pid;

    // Здесь мы узнаём PID и выводим его
    public function __construct()
    {
        $this->pid = getmypid();
        $this->message('PID=' . $this->pid);
    }

    // Вспомогательный метод, чтобы не писать везде "\n"
    protected function message(string $message)
    {
        echo date('d.m.Y H:i:s') . ' ' . $message . "\n";
    }

    // Метод, который и будет выполнять полезную работу
    abstract protected function handle(): \Generator;

    // Метод "запуска" воркера
    final public function __invoke(): void
    {
        foreach ($this->handle() as $done) {
            // Здесь будет находиться точка прерывания воркера
            $this->message($done . '...');
        };
        echo "\n";
    }

}
