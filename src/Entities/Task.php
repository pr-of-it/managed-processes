<?php

namespace App\Entities;

use App\Values\TaskExecuteValue;
use App\Values\TaskResultValue;
use Ramsey\Uuid\Uuid;
use Runn\ValueObjects\Entity;
use Runn\ValueObjects\Values\DateTimeValue;
use Runn\ValueObjects\Values\IntValue;
use Runn\ValueObjects\Values\StringValue;

/**
 * Сущность "задача"
 *
 * Class Task
 * @property string $key
 * @property string $worker
 * @property TaskExecuteValue $execute
 * @property \DateTime $created_at
 * @property int $pid
 * @property int $todo
 * @property int $done
 * @property \DateTime $updated_at
 * @property string $finished_at
 * @property string $result
 * @package App\Entities
 */
class Task extends Entity
{

    const PK_FIELDS = ['key'];

    /**
     * Схема структуры данных
     * @var array
     */
    protected static $schema = [
        'key' =>        ['class' => StringValue::class,     'default' => null],
        'worker' =>     ['class' => StringValue::class],
        'execute' =>    ['class' => TaskExecuteValue::class],
        'created_at' => ['class' => DateTimeValue::class,   'default' => null],
        'pid' =>        ['class' => IntValue::class,        'default' => null],
        'todo' =>       ['class' => IntValue::class,        'default' => 100],
        'done' =>       ['class' => IntValue::class,        'default' => 0],
        'updated_at' => ['class' => DateTimeValue::class,   'default' => null],
        'finished_at'=> ['class' => DateTimeValue::class,   'default' => null],
        'result' =>     ['class' => TaskResultValue::class, 'default' => null],
    ];

    /**
     * Метод, генерирующий ключ для хранения сущности в Redis
     * @return string
     */
    public function makeKey()
    {
        return
            'task:' .
            $this->worker . ':' .
            Uuid::uuid4();
    }

    /**
     * Создает сущность "Задача" на основе данных из Redis
     * @param string $key Ключ задачи
     * @param string $dataFromRedis Данные из Redis
     * @return Task
     * @throws \Exception
     */
    public static function createFromRedis(string $key, string $dataFromRedis): self
    {
        $taskData = json_decode($dataFromRedis, true);

        // На случай, если из Redis ничего не пришло
        if (empty(array_filter($taskData))) {
            return null;
        }

        // Определяем класс "полетного задания" для воркера
        $workerExecuteValueClass = '\App\Values\\' . $taskData['worker'] . 'WorkerExecuteValue';
        $taskData['execute'] = new $workerExecuteValueClass($taskData['execute']);

        // Добавляем к данным ключ задачи
        $taskData = array_merge($taskData, ['key' => $key]);

        return new self ($taskData);
    }

}
