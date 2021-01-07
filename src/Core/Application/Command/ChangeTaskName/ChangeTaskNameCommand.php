<?php
declare(strict_types=1);

namespace App\Core\Application\Command\ChangeTaskName;

use Ramsey\Uuid\UuidInterface;

class ChangeTaskNameCommand
{
    /**
     * @var UuidInterface
     */
    private UuidInterface $taskId;

    private string $name;

    public function __construct(UuidInterface $taskId, string $name)
    {
        $this->taskId = $taskId;
        $this->name   = $name;
    }

    /**
     * @return UuidInterface
     */
    public function getTaskId(): UuidInterface
    {
        return $this->taskId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
