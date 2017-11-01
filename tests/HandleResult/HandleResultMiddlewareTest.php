<?php


namespace Necronru\Tactitian\Tests\HandleResult;

use Necronru\Tactitian\Middleware\HandleResult\HandleResultMiddleware;
use Necronru\Tactitian\Middleware\HandleResult\HandleResultQuery;
use Necronru\Tactitian\Tests\CommandBusTestCase;
use Necronru\Tactitian\Tests\Message\SuccessTestMessage;
use Necronru\Tactitian\Tests\SimpleContainer;

class HandleResultMiddlewareTest extends CommandBusTestCase
{
    public static function createMessageBus($middleware = [])
    {
        return parent::createMessageBus(array_merge($middleware, [
            new HandleResultMiddleware(new SimpleContainer([
                TestModifer::class => new TestModifer()
            ]))
        ]));
    }

    public function testModifyQuery()
    {
        $messageBus = static::createMessageBus();

        $result = $messageBus->handle(
            new HandleResultQuery(new SuccessTestMessage(), function($returnValue, HandleResultQuery $message) {
                return $returnValue . '2';
            })
        );

        $this->assertEquals($result, 'test2');

        $result = $messageBus->handle(
            new HandleResultQuery(new SuccessTestMessage(), [
                function($returnValue) { return $returnValue . '2';},
                function($returnValue) { return $returnValue . '2';},
            ])
        );

        $this->assertEquals($result, 'test22');

        $this->assertEquals(
            $messageBus->handle(new HandleResultQuery(new SuccessTestMessage(), TestModifer::class)),
            'test__invoke'
        );

        $this->assertEquals(
            $messageBus->handle(new HandleResultQuery(new SuccessTestMessage(), [TestModifer::class, TestModifer::class])),
            'test__invoke__invoke'
        );
    }
}