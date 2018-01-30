<?php

namespace App\Workers;

use App\Worker;

class Test extends Worker
{

    protected function handle(): \Generator
    {
        foreach ([1, 2, 3] as $done) {
            // Введем задержку для наглядности и отладки
            sleep(1);
            yield $done;
        }
    }

}
