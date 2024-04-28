<?php

namespace Scody\QueueManager;

use Scody\QueueManager\Interfaces\LoggerInterface;

/**
 * Класс для управления очередью задач.
 */
class QueueManagerLog implements LoggerInterface
{
    public bool $debug = false;
    public bool $debugShow = false;
    public string $logFile = 'queue.log';

    /**
     * Конструктор класса FileLogger.
     *
     */
    public function __construct($logFile)
    {
        $this->setLogFile($logFile);
    }

    /**
     * @return string
     */
    public function getLogFile(): string
    {
        return $this->logFile;
    }

    /**
     * @param   string  $logFile
     */
    public function setLogFile(string $logFile): void
    {
        $this->logFile = $logFile;
    }

    /**
     * Записывает сообщение в лог файл.
     *
     * @param   string  $message  Сообщение для записи в лог.
     * @param   string  $type     Тип сообщения ('info' или 'error').
     */
    public function log(string $message, string $type = 'info'): void
    {
        $logTimestamp = date('[Y-m-d H:i:s]');
        $logMessage   = "$logTimestamp [$type] $message\n";

        if ($this->debugShow)
        {
            if ((php_sapi_name() === 'cli'))
            {
                print_r($logMessage . "\r\n");
            }
            else
            {
                print_r('<pre>');
                print_r($logMessage . "\r\n");
                print_r('</pre>');
            }
        }

        if ($this->debug)
        {
            file_put_contents($this->getLogFile(), $logMessage, FILE_APPEND);
        }
    }
}
