<?php
declare(strict_types=1);

namespace App\Core\Domain\Model\Task\Event;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 */
class TaskEvent
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private UuidInterface $taskId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $value;

    /**
     * @ORM\Column(type="datetime_immutable", options={"default"="CURRENT_TIMESTAMP"}, nullable=false)
     */
    private \DateTimeImmutable $createdAt;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return UuidInterface
     */
    public function getTaskId(): UuidInterface
    {
        if (is_string($this->taskId)) {
            $this->taskId = Uuid::fromString($this->taskId);
        }

        return $this->taskId;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}