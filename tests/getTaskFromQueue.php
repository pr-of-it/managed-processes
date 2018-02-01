#!/usr/bin/env php
<?php

use App\Entities\Task;
use App\Repositories\TasksRepository;
use App\Values\TestWorkerExecuteValue;

// Подключаем автозагрузку своего проекта
require __DIR__ . '/../autoload.php';

// Получаем ID очереди
$queue = $argv[1];

$task = TasksRepository::instance()->findClosestInQueue($queue);

if (!empty($task)) {
    var_dump( $task->getValue() );
}

echo "\n";
