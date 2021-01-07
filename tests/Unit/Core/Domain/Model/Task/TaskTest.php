<?php

namespace App\Tests\Unit\Core\Domain\Model\Task;

use App\Core\Domain\Model\Task\Exception\NameToShortException;
use App\Core\Domain\Model\Task\Status;
use App\Core\Domain\Model\Task\Task;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class TaskTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_keep_name_of_task()
    {
        $task = new Task(Uuid::uuid4(), Uuid::uuid4(), 'name', Status::UNDONE);

        Assert::assertEquals('name', $task->getName());
    }

    /**
     * @test
     */
    public function it_allows_to_change_the_name()
    {
        $task = new Task(Uuid::uuid4(), Uuid::uuid4(), 'name', Status::UNDONE);

        $task->changeName('new name');

        Assert::assertEquals('new name', $task->getName());
    }

    /**
     * @test
     */
    public function it_should_fail_on_to_short_name()
    {
        self::expectException(NameToShortException::class);

        $task = new Task(Uuid::uuid4(), Uuid::uuid4(), 'name', Status::UNDONE);

        $task->changeName('n');
    }

    /**
     * @test
     */
    public function it_can_be_marked_as_done()
    {
        $task = new Task(Uuid::uuid4(), 'name', Uuid::uuid4(), Status::UNDONE);

        $task->markAsDone();

        Assert::assertEquals(Status::DONE, $task->getStatus());
    }

    /**
     * @test
     */
    public function it_can_be_marked_as_undone()
    {
        $task = new Task(Uuid::uuid4(), 'name', Uuid::uuid4(), Status::DONE);

        $task->markAsUndone();

        Assert::assertEquals(Status::UNDONE, $task->getStatus());
    }

    /**
     * @test
     */
    public function it_belongs_to_one_user()
    {
        $userId = Uuid::uuid4();
        $task   = new Task(Uuid::uuid4(), 'name', $userId, Status::UNDONE);

        Assert::assertTrue($userId->equals($task->getUserId()));
    }

    /**
     * @test
     */
    public function it_can_be_reassigned_to_different_user()
    {
        $userId = Uuid::uuid4();
        $task   = new Task(Uuid::uuid4(), 'name', $userId, Status::UNDONE);

        $newUserId = Uuid::uuid4();

        $task->assignToUser($newUserId);
        Assert::assertTrue($newUserId->equals($task->getUserId()));
    }
}
