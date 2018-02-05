<?php

declare(ticks=1);

namespace App;

/**
 * Абстрактный класс "воркера" - рабочего процесса
 *
 * Class Worker
 * @package App
 */
abstract class Worker
{
    // Список перехватываемых сигналов и их обработчики
    public const SIGNALS = [
        SIGTERM => 'sigTermHandler',
        SIGTSTP => 'sigTstpHandler',
        SIGCONT => 'sigContHandler',
    ];

    // Здесь хранится PID текущего процесса
    protected $pid;

    // Флаг, означающий, что пора заканчивать работу
    protected $terminate = false;

    // Флаг, означающий, что нужно приостановить работу
    protected $pause = false;

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
        // Основной рабочий цикл воркера
        foreach ($this->handle() as $done) {

            $this->message($done . '...');

            // Корректный выход из процесса по завершении кванта работы
            if ($this->terminate) {
                $this->message('Воркер был прерван');
                exit(0);
            }

            // Бесконечный цикл в паузе
            while ($this->pause) {
                $this->message('Воркер на паузе');
                // Спим секунду до следующей проверки, чтобы не нагружать CPU
                sleep(1);
            }

        };

        $this->message('Воркер закончил работу');
    }

    // Обработчик сигнала SIGTERM
    public function sigTermHandler(int $signo, $signinfo)
    {
        if (SIGTERM === $signo) {
            $this->pause = true;
        }
    }

    // Обработчик сигнала SIGTSTP
    public function sigTstpHandler(int $signo, $signinfo)
    {
        if (SIGTSTP === $signo) {
            $this->pause = true;
        }
    }

    // Обработчик сигнала SIGCONT
    public function sigContHandler(int $signo, $signinfo)
    {
        if (SIGCONT === $signo) {
            $this->pause = false;
        }
    }

}
