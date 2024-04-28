<?php

namespace Scody\QueueManager\Interfaces;

/**
 * Интерфейс для логирования сообщений.
 */
interface LoggerInterface
{

    /**
     * Логирует сообщение.
     *
     * @param   string  $message  Текст сообщения.
     * @param   string  $level    Уровень логирования (например, 'info', 'error').
     *
     * @return void
     */
    public function log(string $message, string $level = 'info'): void;
}

