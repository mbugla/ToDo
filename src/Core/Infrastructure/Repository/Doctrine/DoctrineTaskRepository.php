<?php

namespace App\Core\Infrastructure\Repository\Doctrine;

use App\Core\Domain\Model\Task\Event\AssignedUserChangedEvent;
use App\Core\Domain\Model\Task\Event\NameChangedEvent;
use App\Core\Domain\Model\Task\Event\StatusChangedEvent;
use App\Core\Domain\Model\Task\Event\TaskEvent;
use App\Core\Domain\Model\Task\Task;
use App\Core\Domain\Model\Task\TaskRepositoryInterface;
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
        /** @var TaskEvent[] $events */
        $events = $this->findBy(['userId' => $userId->toString()]);

        return $this->getTasks($events);
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
            $taskEvent =
                TaskEvent::fromDomainEvent($pending, $task->getUserId());

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

    /**
     * @param array $events
     *
     * @return array|Task[]
     */
    private function getTasks(array $events): array
    {
        $tasksEvents = [];
        $tasks       = [];

        foreach ($events as $event) {
            $tasksEvents[$event->getTaskId()->toString()][] = $event;
        }

        foreach ($tasksEvents as $taskId => $taskEvents) {
            $tasks[] =
                $this->getTask($taskEvents, Uuid::fromString($taskId));
        }

        return $tasks;
    }
}
