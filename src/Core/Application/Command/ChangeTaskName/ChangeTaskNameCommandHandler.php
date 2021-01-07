<?php

namespace App\Core\Application\Command\ChangeTaskName;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ChangeTaskNameCommandHandler implements MessageHandlerInterface
{
    public function __invoke(ChangeTaskNameCommand $changeTaskNameCommand)
    {
    }
}
