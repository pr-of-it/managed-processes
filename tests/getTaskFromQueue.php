#!/usr/bin/env php
<?php

use App\Entities\Task;
use App\Repositories\TasksRepository;
use App\Values\TestWorkerExecuteValue;

// Подключаем автозагрузку своего проекта
require __DIR__ . '/../autoload.php';

// Создаем задачу
$task = new Task([
    'worker' => 'Test',
    'execute' => new TestWorkerExecuteValue(10),
]);

// Сохраняем задачу в Redis
TasksRepository::instance()->store($task);

// Добавляем в очередь для заданного воркера
TasksRepository::instance()->addToQueue('Test', $task);

echo $task->key;
echo "\n";
