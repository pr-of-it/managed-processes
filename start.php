#!/usr/bin/env php
<?php

// Подключаем автозагрузку своего проекта
require __DIR__ . '/autoload.php';

// Получаем имя класса воркера
$className = '\App\Workers\\' . $argv[1];

// Создаем экземпляр класса
$worker = new $className;

// Устанавливаем обработчики сигналов
foreach ($className::SIGNALS as $signal => $handler) {
    pcntl_signal($signal, [$worker, $handler]);
}

// ...Старт!
$worker();
