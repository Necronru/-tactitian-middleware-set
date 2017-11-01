<?php


namespace Necronru\Tactitian\Tests\GenericMessage;


use Necronru\Tactitian\Middleware\GenericMessage\GenericMessage;
use Necronru\Tactitian\Middleware\GenericMessage\GenericMessageMiddleware;
use Necronru\Tactitian\Tests\CommandBusTestCase;
use Necronru\Tactitian\Tests\Message\SuccessTestMessage;

class GenericMessageTest extends CommandBusTestCase
{
    public function testMiddleware()
    {
        $messageBus = static::createMessageBus([
            new GenericMessageMiddleware()
        ]);

        $result = $messageBus->handle(new GenericMessage(new SuccessTestMessage('generic message test')));
        $this->assertEquals('generic message test', $result);

    }

}