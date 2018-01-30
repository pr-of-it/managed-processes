#!/usr/bin/env php
<?php

// Подключаем автозагрузку своего проекта
require __DIR__ . '/autoload.php';

// Получаем имя класса воркера
$className = '\App\Workers\\' . $argv[1];

// Создаем экземпляр класса
$worker = new $className;

// ...Старт!
$worker();
