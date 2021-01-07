<?php

namespace App\Tests\Unit\Core\Application\REST\Task;

use App\Core\Application\REST\Task\CreateTaskAction;
use App\Core\Domain\Model\User\User;
use App\Core\Domain\Model\User\UserFetcherInterface;
use App\Tests\Unit\Core\Domain\Model\User\UserTest;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateTaskActionTest extends TestCase
{
    /**
     * @test
     */
    public function should_create_new_task_for_user()
    {
        $messageBus  = $this->createMock(MessageBusInterface::class);
        $userFetcher = $this->createMock(UserFetcherInterface::class);

        $user = new User(
            Uuid::uuid4(),
            'user',
            'pass',
            UserTest::getUniqueUsernameConstraint()
        );

        $userFetcher->expects($this->once())
            ->method('fetchRequiredUser')
            ->willReturn($user);

        $messageBus->expects($this->once())->method('dispatch');
        $action = new CreateTaskAction($messageBus, $userFetcher);

        $req = $this->createMock(Request::class);
        $req->expects($this->once())->method('getContent')->willReturn('{"name": "first task"}');

        $action($req);
    }
}
