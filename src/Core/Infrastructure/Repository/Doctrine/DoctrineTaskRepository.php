<?php

namespace App\Core\Infrastructure\Repository\Doctrine;

use App\Core\Domain\Model\Task\Event\AssignedUserChangedEvent;
use App\Core\Domain\Model\Task\Event\NameChangedEvent;
use App\Core\Domain\Model\Task\Event\StatusChangedEvent;
use App\Core\Domain\Model\Task\Event\TaskEvent;
use App\Core\Domain\Model\Task\Task;
use App\Core\Domain\Model\Task\TaskRepositoryInterface;
use App\Core\Domain\Model\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class DoctrineTaskRepository extends ServiceEntityRepository implements TaskRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        string $entityClass = TaskEvent::class
    ) {
        parent::__construct($registry, $entityClass);
    }

    public function findByUser(UuidInterface $userId): array
    {
        // TODO: Implement findByUser() method.
    }

    public function findByUuid(UuidInterface $id): ?Task
    {
        /** @var TaskEvent[] $events */
        $events = $this->findBy(['taskId' => $id->toString()]);

        return $this->getTask($events, $id);
    }

    public function save(Task $task): void
    {
        $this->createQueryBuilder('q')
            ->delete(TaskEvent::class, 'te')
            ->where('te.taskId = :tid')
            ->setParameter('tid', $task->getId()->toString())
            ->getQuery()
            ->execute();

        foreach ($task->getPendingEvents() as $pending) {
            $taskEvent = new TaskEvent();
            $taskEvent
                ->setTaskId($task->getId()->toString())
                ->setType($pending::getType())
                ->setValue($pending->getValue())
                ->setCreatedAt($pending->getCreatedAt());
            $this->_em->persist($taskEvent);
        }

        $this->_em->flush();
    }

    public function remove(Task $task): void
    {
        $this->createQueryBuilder('q')
            ->delete(TaskEvent::class, 'te')
            ->where('te.taskId = :tid')
            ->setParameter('tid', $task->getId()->toString())
            ->getQuery()
            ->execute();
    }

    /**
     * @param array         $events
     * @param UuidInterface $id
     *
     * @return Task|null
     */
    protected function getTask(array $events, UuidInterface $id): ?Task
    {
        $domainEvents = [];

        foreach ($events as $event) {
            switch ($event->getType()) {
                case NameChangedEvent::TYPE:
                    $domainEvents[] =
                        new NameChangedEvent(
                            $id,
                            $event->getValue(),
                            $event->getCreatedAt()
                        );
                    break;
                case StatusChangedEvent::TYPE:
                    $domainEvents[] =
                        new StatusChangedEvent(
                            $id,
                            $event->getValue(),
                            $event->getCreatedAt()
                        );
                    break;
                case AssignedUserChangedEvent::TYPE:
                    $domainEvents[] = new AssignedUserChangedEvent(
                        $id,
                        Uuid::fromString($event->getValue()),
                        $event->getCreatedAt()
                    );
                    break;
            }
        }

        if (count($domainEvents) < 1) {
            return null;
        }

        return Task::recreateFrom($id, $domainEvents);
}
}
