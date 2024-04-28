<?php

namespace Scody\QueueManager;

use Exception;
use PDOException;
use Scody\QueueManager\Interfaces\LoggerInterface;

/**
 * Класс для управления очередью задач.
 */
class QueueManager
{
    private array $paramDB;
    protected mixed $connection = null;
    private LoggerInterface $logger;

    /**
     * Конструктор класса QueueManager.
     *
     * @param   array   $paramDB
     * @param   string  $logFile
     */
    public function __construct(array $paramDB, string $logFile = 'queue.log')
    {
        $this->setConfDB($paramDB);
        $this->setLogger(new QueueManagerLog($logFile));
    }

    public function init(): void
    {
        $this->getConnection();
    }

    public function getConnection()
    {
        if (is_null($this->connection))
        {
            $storageClass = __NAMESPACE__ . '\Storage\\' . ucfirst($this->paramDB['sheme']);

            if (class_exists($storageClass))
            {
                $this->connection = new $storageClass($this->paramDB);
            }
        }

        return $this->connection;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        if (empty($this->logger))
        {
            $this->setLogger(new QueueManagerLog());
        }

        return $this->logger;
    }

    /**
     * @param   LoggerInterface  $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * @return bool
     */
    public function isDebugShow(): bool
    {
        return $this->getLogger()->debugShow;
    }

    /**
     * @param   bool  $debugShow
     */
    public function setDebugShow(bool $debugShow): void
    {
        $this->getLogger()->debugShow = $debugShow;
    }

    /**
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->getLogger()->debug;
    }

    /**
     * @param   bool  $debug
     */
    public function setDebug(bool $debug): void
    {
        $this->getLogger()->debug = $debug;
    }

    /**
     * @return string
     */
    public function getLogFile(): string
    {
        return $this->getLogger()->logFile;
    }

    /**
     * @param   string  $logFile
     */
    public function setLogFile(string $logFile): void
    {
        $this->getLogger()->logFile = $logFile;
    }

    /**
     * Установка параметров DB
     *
     * @param   array  $param
     *
     * @return void
     *
     * @created by: SCaeR
     * @since   version 1.0.0
     */
    private function setConfDB(array $param = []): void
    {
        $default = [
            'host'     => '',
            'user'     => '',
            'password' => '',
            'sheme'    => 'sqlite',//sqlite  / mysql
            'db'       => 'QueueManager.db',// file / dbName / :memory:
            'table'    => 'queue',
        ];

        $param         = array_intersect_key($param, $default);
        $this->paramDB = array_merge($default, $param);
    }

    /**
     * Добавляет новую задачу в очередь.
     *
     * @param   array        $data  Дополнительные данные задачи в виде массива (по умолчанию []).
     * @param   string|null  $name  Название задачи (по умолчанию null).
     * @param   string|null  $code  Код задачи (по умолчанию null).
     */
    public function addTask(array $data = [], string $code = null, ?string $name = null): void
    {
        try
        {
            // Генерируем автоматическое имя задачи, если не задано явно
            if ($name === null)
            {
                $name = 'task_' . time();
            }

            // Генерируем автоматический код задачи, если не задан явно
            if ($code === null)
            {
                $code = 'code_' . time();
            }

            // Проверяем наличие префикса в коде задачи
            if (!str_contains($code, "\\"))
            {
                $code = __NAMESPACE__ . '\Task\QueueManagerTask' . ucfirst($code);
            }

            // Преобразуем массив $additionalData в формат JSON
            $dataJson = serialize($data);

            // Проверяем, удалось ли преобразовать массив в JSON
            if ($dataJson === false)
            {
                throw new Exception('Error converting additional data to JSON');
            }

            if ($this->connection->add($code, $dataJson, $name))
            {
                $this->getLogger()->log("Task added to queue: $name");
            }

        }
        catch (PDOException|Exception $e)
        {
            $this->getLogger()->log('Error adding task to queue: ' . $e->getMessage(), 'error');
        }
        catch (Exception $e)
        {
            $this->getLogger()->log('Error converting additional data to JSON: ' . $e->getMessage(), 'error');
        }
    }

    /**
     * Выполняет следующую задачу из очереди.
     */
    public function executeNextTask(): void
    {
        try
        {
            $row = $this->connection->getNext(!$this->isDebug());

            if ($row)
            {
                $taskId = $row['id'];
                $name   = $row['name'];
                $code   = $row['code'];
                $data   = unserialize($row['data']);

                $taskClassName = $code;
                if (class_exists($taskClassName))
                {
                    $task = new $taskClassName();
                    $task->execute($data);

                    if ($this->connection->setCompleted($taskId))
                    {
                        $this->getLogger()->log("Task executed: $name ($code)");
                    }
                    else
                    {
                        $this->getLogger()->log("ERROR Task setCompleted: $name ($code)");
                    }
                }
                else
                {
                    $this->getLogger()->log("Task class not found for code: $name ($code)", 'error');
                }
            }
            else
            {
                $this->getLogger()->log('No pending tasks found');
            }
        }
        catch (PDOException $e)
        {
            $this->getLogger()->log('Error executing task: ' . $e->getMessage(), 'error');
        }
    }

}
