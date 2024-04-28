<?php

require_once '../vendor/autoload.php'; // Подключаем автозагрузчик Composer

use Scody\QueueManager\QueueManager;

/*
// Example for MySql use
$paramDB = [
    'host'     => 'localhost',
    'user'     => 'root',
    'password' => '',
    'sheme'    => 'mysql',
    'db'       => 'QueueManager',
    // 'table'    => 'queue',
];
// */

// /*
// Example for SqLite use
$paramDB = [
    'sheme' => 'sqlite',
    'db'    => ':memory:',
];
// */

// Создаем экземпляр класса QueueManager
$queueManager = new QueueManager($paramDB);

// $queueManager->setDebug(true);
$queueManager->setDebugShow(true);
$queueManager->init();

// Добавляем задачу в очередь
$queueManager->addTask([
    'param1' => 'value1',
    'param2' => 'value2',
],
    'Example',// QueueManager\Task\QueueManagerTaskExample
    'Example ' . time(),
);

// Выполняем следующую задачу из очереди
$queueManager->executeNextTask();
