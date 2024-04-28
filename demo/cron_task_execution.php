<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);

// Регистрируем функцию автозагрузки классов
// spl_autoload_register(function ($className) {
//     $classFile = __DIR__ . '/' . $className . '.php';
//     if (file_exists($classFile))
//     {
//         require_once $classFile;
//     }
// });

require_once 'vendor/autoload.php'; // Подключаем автозагрузчик Composer

use src\QueueManager;

// Создаем экземпляр класса QueueManager
$queueManager = new QueueManager(
    'root',
    '',
    'localhost',
    'QueueManager',
    'queue',
    __DIR__ . '/queue.log'
);

// Выполняем следующую задачу из очереди
$queueManager->executeNextTask();
