<?php

namespace App\Repositories;

use App\Entities\Task;
use App\Entities\Tasks;
use Runn\Core\Config;
use Runn\Core\SingletonInterface;
use Runn\Core\SingletonTrait;
use Runn\Fs\Files\PhpFile;

/**
 * Репозиторий задач
 *
 * Class TasksRepository
 * @package App\Repositories
 */
class TasksRepository implements SingletonInterface
{

    use SingletonTrait;

    protected const ITEM_CLASS = Task::class;
    protected const ITEM_COLLECTION_CLASS = Tasks::class;

    protected $redis;

    protected function __construct()
    {
        $config = new Config(new PhpFile(__DIR__ . '/../../config.php'));
        $this->redis = new \Redis();
        $this->redis->connect($config->redis->host, $config->redis->port);
    }

    /**
     * Сохраняем (обновляем) задачу
     * При создании присваиваем задаче ключ и выставляем время создания
     * При обновлении прописываем время обновления
     * @param Task $task
     * @return bool
     */
    public function store(Task $task): bool
    {
        if ($task->issetPrimaryKey()) {
            $task->updated_at = new \DateTime();
        } else {
            $task->key = $task->makeKey();
            $task->created_at = new \DateTime();
        }

       return  $this->redis->set($task->getPrimaryKey(), json_encode($task));
    }

    /**
     * Ставит задачу в очередь
     * @param string $queueId Идентификатор очереди задач
     * @param Task $task
     */
    public function addToQueue(string $queueId, Task $task): void
    {
        if (!$task->issetPrimaryKey()) {
            $this->store($task);
        }

        $this->redis->rPush('tasks:queue:' . $queueId, $task->getPrimaryKey());
    }

    /**
     * Возвращает ближайшую в очереди задачу, удаляя ее из очереди
     * @param string $queueId Идентификатор очереди задач
     * @return Task|null
     * @throws \Exception
     */
    public function findClosestInQueue(string $queueId): ?Task
    {
        $key = $this->redis->lPop('tasks:queue:' . $queueId);
        if (false === $key) {
            return null;
        }

        $data = $this->redis->get($key);

        return Task::createFromRedis($key, $data);
    }

}
