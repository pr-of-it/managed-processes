<?php

namespace App\Workers;

use App\Worker;

class Test extends Worker
{

    protected function handle(): \Generator
    {
        foreach ([0, 1, 2, 3, 4, 5, 6, 7, 8, 9] as $done) {
            // Введем задержку для наглядности и отладки
            sleep(1);
            yield $done;
        }
    }

}
