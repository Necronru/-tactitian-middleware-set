<?php


namespace Necronru\Tactitian\Tests\ContextMessage;


use Necronru\Tactitian\Middleware\ContextMessage\ContextMessage;
use Necronru\Tactitian\Middleware\ContextMessage\ContextMessageMiddleware;
use Necronru\Tactitian\Tests\CommandBusTestCase;
use Necronru\Tactitian\Tests\Message\SuccessTestMessage;

class ContextMessageTest extends CommandBusTestCase
{
    public function testMiddleware()
    {
        $messageBus = static::createMessageBus([
            new ContextMessageMiddleware()
        ]);

        $result = $messageBus->handle(new ContextMessage(new SuccessTestMessage('generic message test')));
        $this->assertEquals('generic message test', $result);

    }

}