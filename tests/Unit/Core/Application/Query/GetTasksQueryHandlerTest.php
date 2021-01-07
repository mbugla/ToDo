<?php

namespace App\Tests\Unit\Core\Application\Query;

use App\Core\Application\Query\GetTasksQuery;
use App\Core\Application\Query\GetTasksQueryHandler;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class GetTasksQueryHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_return_tasks_for_user()
    {
        $query = new GetTasksQuery();

        $handler = new GetTasksQueryHandler();

        $tasks = $handler($query);

        Assert::assertCount(2, $tasks);
    }
}
