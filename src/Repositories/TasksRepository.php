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

}
