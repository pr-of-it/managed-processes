<?php

namespace App\Workers;

use App\Worker;

class Test extends Worker
{

    protected function handle()
    {
        echo 'Hello, world!';
        echo "\n";
    }

}
