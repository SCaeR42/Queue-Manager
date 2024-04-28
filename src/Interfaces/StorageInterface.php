<?php

namespace Scody\QueueManager\Interfaces;

/**
 * Интерфейс для хранилища задач в очереди.
 */
interface StorageInterface
{
    /**
     * Добавляет новую задачу в очередь.
     *
     * @param   string  $code  Код задачи.
     * @param   string  $data  Данные задачи в формате JSON.
     * @param   string  $name  Название задачи.
     *
     * @return bool Возвращает true в случае успешного добавления задачи, иначе false.
     */
    public function add(string $code, string $data, string $name): bool;

    /**
     * Получает следующую задачу из очереди.
     *
     * @param   mixed  $changeStatus
     *
     * @return array|null Возвращает ассоциативный массив с информацией о задаче или null, если очередь пуста.
     */
    public function getNext(mixed $changeStatus): ?array;

    /**
     * Помечает задачу как выполненную.
     *
     * @param   int  $taskId  Идентификатор задачи.
     *
     * @return bool Возвращает true в случае успешной пометки задачи, иначе false.
     */
    public function setCompleted(int $taskId): bool;
}


