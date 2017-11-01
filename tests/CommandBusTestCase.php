<?php


namespace Necronru\Tactitian\Tests;

use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\InvokeInflector;
use Necronru\Tactitian\Tests\Handler\FailTestHandler;
use Necronru\Tactitian\Tests\Handler\SuccessTestHandler;
use Necronru\Tactitian\Tests\Message\FailTestMessage;
use Necronru\Tactitian\Tests\Message\SuccessTestMessage;
use PHPUnit\Framework\TestCase;

abstract class CommandBusTestCase extends TestCase
{
    public static function createMessageBus($middleware = [])
    {
        $middleware = array_merge($middleware, [
            new CommandHandlerMiddleware(
                new ClassNameExtractor(),
                new InMemoryLocator([
                    SuccessTestMessage::class => new SuccessTestHandler(),
                    FailTestMessage::class    => new FailTestHandler(),
                ]),
                new InvokeInflector()
            ),
        ]);

        return new CommandBus($middleware);
    }

}