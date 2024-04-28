# QueueManager

QueueManager is a PHP class for managing a task queue in a MySQL database.


## Requirements

- PHP 8.0 or higher
- MySQL/SqLite database

## Installation


You can simply include the `QueueManager.php` file in your project.

```php
require_once 'QueueManager.php';
```

## Usage

```php

require_once 'QueueManager.php';

// Creating an instance of the QueueManager class with database connection parameters
$queueManager = new \Scody\QueueManager\QueueManager([
     'sheme' => 'sqlite',
     'db' => 'path/to/your/database.db',
]);

// Add a new task to the queue
$queueManager->addTask(['param1' => 'value1'], 'task_code', 'Task Name');

// Execute the next task from the queue
$queueManager->executeNextTask();

```

## Logging

QueueManager supports logging using the LoggerInterface interface. By default, logging to a file is used. You can create your own LoggerInterface implementation for logging to other sources, such as a database or third-party services.

## Testing

To run tests, you can run PHPUnit from the command line, specifying the path to the test file:

```shell
./vendor/bin/phpunit tests/QueueManagerTest.php
```


## License

This project is licensed under the MIT License - see [LICENSE](license.txt) for details.