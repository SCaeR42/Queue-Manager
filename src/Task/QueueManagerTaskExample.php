<?php

namespace Scody\QueueManager\Task;

/**
 * Базовый класс для задач менеджера очереди.
 */
class QueueManagerTaskExample
{
    /**
     * Выполняет задачу.
     *
     * @param   array  $data  Данные задачи.
     */
    public function execute(array $data): void
    {
        print_r('<pre>');
        print_r('$data: ');
        print_r($data);
        print_r('</pre>');
    }
}

