<?php


namespace Necronru\Tactitian\Tests\MessageLog;


use League\Tactician\CommandBus;
use Monolog\Logger;
use Necronru\Tactitian\Middleware\MessageLog\MessageLogMiddleware;
use Necronru\Tactitian\Middleware\MessageLog\SimpleGuidExtractor;
use Necronru\Tactitian\Middleware\MessageLog\SimpleMessageSerializer;
use Necronru\Tactitian\Tests\CommandBusTestCase;
use Necronru\Tactitian\Tests\Message\FailTestMessage;
use Necronru\Tactitian\Tests\Message\SuccessTestMessage;

class MessageLogMiddlewareTest extends CommandBusTestCase
{
    public static function createMessageBus($logHandlers = [], $middleware = [])
    {
        $logger = new Logger('tests', $logHandlers);

        $guidExtractor = new SimpleGuidExtractor();
        $serializer = new SimpleMessageSerializer();

        return parent::createMessageBus(array_merge($middleware, [
            new MessageLogMiddleware($serializer, $guidExtractor, $logger)
        ]));
    }

    public function testSuccessLogRecord()
    {
        $logsHandler = new \Monolog\Handler\TestHandler();
        $message = new SuccessTestMessage();

        static::createMessageBus([
//            new StreamHandler('php://stdout'),
            $logsHandler
        ])
            ->handle($message)
        ;

        $this->assertCount(2, $logsHandler->getRecords());

        $records = $logsHandler->getRecords();

        $this->assertArrayHasKey('message', $records[0]);
        $this->assertNotEmpty($records[0]['message']);

        $this->assertArrayHasKey('context', $records[0]);
        $this->assertArrayHasKey('guid', $records[0]['context']);
        $this->assertArrayHasKey('payload', $records[0]['context']);
        $this->assertArrayHasKey('messageClass', $records[0]['context']);

        $this->assertEquals(get_class($message), $records[0]['context']['messageClass']);
        $this->assertEquals($message, unserialize($records[0]['context']['payload']));
    }

    /**
     * @expectedExceptionMessage Test exception
     * @expectedException \Exception
     *
     * @throws \Exception
     */
    public function testLogRecord()
    {
        $logsHandler = new \Monolog\Handler\TestHandler();
        $message = new FailTestMessage();

        $commandBus = static::createMessageBus([$logsHandler]);
        $this->assertInstanceOf(CommandBus::class, $commandBus);

        try {
            $commandBus->handle($message);
        } catch (\Exception $ex) {

            $this->assertCount(2, $logsHandler->getRecords());

            $records = $logsHandler->getRecords();

            $this->assertArrayHasKey('message', $records[0]);
            $this->assertNotEmpty($records[0]['message']);

            $this->assertArrayHasKey('guid', $records[1]['context']);
            $this->assertArrayHasKey('exceptionMessage', $records[1]['context']);
            $this->assertArrayHasKey('exceptionCode', $records[1]['context']);
            $this->assertArrayHasKey('exceptionTraceAsString', $records[1]['context']);

            $this->assertEquals($records[1]['context']['guid'], $records[0]['context']['guid']);

            throw $ex;
        }
    }

}