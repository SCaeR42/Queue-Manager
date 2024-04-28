# Менеджер очереди (QueueManager)

Менеджер очереди (QueueManager) - это класс на PHP для управления очередью задач.


## Требования

- PHP 8.0 или выше
- База данных MySQL / SqLite

## Установка


Вы можете просто включить файл `QueueManager.php` в ваш проект.

```php
require_once 'QueueManager.php';
```

## Использование

```php

require_once 'QueueManager.php';

// Создание экземпляра класса QueueManager с параметрами подключения к базе данных
$queueManager = new \Scody\QueueManager\QueueManager([
    'sheme' => 'sqlite',
    'db' => 'path/to/your/database.db',
]);

// Добавление новой задачи в очередь
$queueManager->addTask(['param1' => 'value1'], 'task_code', 'Task Name');

// Выполнение следующей задачи из очереди
$queueManager->executeNextTask();

```

## Логирование

QueueManager поддерживает логирование с помощью интерфейса LoggerInterface. По умолчанию используется логирование в файл. Вы можете создать собственную реализацию LoggerInterface для логирования в другие источники, такие как база данных или сторонние сервисы.

## Тестирование

Для запуска тестов вы можете выполнить PHPUnit из командной строки, указав путь к файлу с тестами:

```shell
./vendor/bin/phpunit tests/QueueManagerTest.php
```


## Лицензия

Этот проект лицензирован по лицензии MIT - подробности см. в файле [LICENSE](license.txt).


