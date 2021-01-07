<?php
declare(strict_types=1);

namespace App\Core\Application\Query;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetTasksQueryHandler implements MessageHandlerInterface
{
    public function __invoke(GetTasksQuery $getTasksQuery)
    {

    }
}
