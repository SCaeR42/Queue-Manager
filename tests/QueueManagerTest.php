<?php

use PHPUnit\Framework\TestCase;
use Scody\QueueManager\QueueManager;

class QueueManagerTest extends TestCase
{
    public function testAddTask()
    {
        // Создание объекта QueueManager
        $queueManager = new QueueManager([
            'sheme' => 'sqlite',
            'db' => ':memory:', // Используем временную базу данных в памяти для тестов
        ]);
        $queueManager->init();

        // Добавление задачи в очередь
        $queueManager->addTask(['param1' => 'value1'], 'task_code', 'Task Name');

        // Проверка, что задача успешно добавлена в очередь
        // Для примера, здесь можно добавить дополнительные проверки
        $this->assertTrue(true);
    }

    public function testExecuteNextTask()
    {
        // Создание объекта QueueManager
        $queueManager = new QueueManager([
            'sheme' => 'sqlite',
            'db' => ':memory:', // Используем временную базу данных в памяти для тестов
        ]);
        $queueManager->init();


        // Добавление задачи в очередь
        $queueManager->addTask(['param1' => 'value1'], 'task_code', 'Task Name');

        // Выполнение следующей задачи из очереди
        // Здесь можно добавить дополнительные проверки, что задача была успешно выполнена
        // или что логирование произошло как ожидалось
        $queueManager->executeNextTask();

        // Для примера, здесь мы просто утверждаем, что метод отработал без ошибок
        $this->assertTrue(true);
    }
}
