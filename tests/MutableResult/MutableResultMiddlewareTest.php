<?php


namespace Necronru\Tactitian\Tests\MutableResult;

use Necronru\Tactitian\Middleware\MutableResult\MutableResultMiddleware;
use Necronru\Tactitian\Middleware\MutableResult\MutateResult;
use Necronru\Tactitian\Tests\CommandBusTestCase;
use Necronru\Tactitian\Tests\Message\SuccessTestMessage;
use Necronru\Tactitian\Tests\SimpleContainer;

class MutableResultMiddlewareTest extends CommandBusTestCase
{
    public static function createMessageBus($middleware = [])
    {
        return parent::createMessageBus(array_merge($middleware, [
            new MutableResultMiddleware(new SimpleContainer([
                TestMutator::class => new TestMutator()
            ]))
        ]));
    }

    public function testModifyQuery()
    {
        $messageBus = static::createMessageBus();

        $result = $messageBus->handle(
            new MutateResult(new SuccessTestMessage(), function($returnValue, MutateResult $message) {
                return $returnValue . '2';
            })
        );

        $this->assertEquals($result, 'test2');

        $result = $messageBus->handle(
            new MutateResult(new SuccessTestMessage(), [
                function($returnValue) { return $returnValue . '2';},
                function($returnValue) { return $returnValue . '2';},
            ])
        );

        $this->assertEquals($result, 'test22');

        $this->assertEquals(
            $messageBus->handle(new MutateResult(new SuccessTestMessage(), TestMutator::class)),
            'test__invoke'
        );

        $this->assertEquals(
            $messageBus->handle(new MutateResult(new SuccessTestMessage(), [TestMutator::class, TestMutator::class])),
            'test__invoke__invoke'
        );
    }
}