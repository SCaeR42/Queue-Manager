# QueueManager

QueueManager is a PHP class for managing a queue of tasks.


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

QueueManager supports logging using the LoggerInterface interface. By default, logging to a file is used. You can create your own LoggerInterface implementation for logging to other sources such as a database or third party services.

## Testing

To run tests, you can run PHPUnit from the command line, specifying the path to the test file:

```shell
./vendor/bin/phpunit tests/QueueManagerTest.php
```

## Links

* Website: [SpaceCodinG.net](https://spacecoding.net/)
* GitHub: [@SCaeR42](https://github.com/SCaeR42)
* GitLab: [@SCaeR42](https://gitlab.com/spacecoding)

## Support the project

Give a ⭐️ if this project helped you!

## License

Copyright (C) 2024 SCaeR42@[SpaceCodinG.net](https://spacecoding.net/)

Licensed under [MIT](license.txt).