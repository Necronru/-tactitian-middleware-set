<?php


namespace Necronru\Tactitian\Tests\TypeCast;

use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\InvokeInflector;
use Necronru\Tactitian\Middleware\TypeCast\CastTo;
use Necronru\Tactitian\Middleware\TypeCast\TypeCastMessageMiddleware;
use Necronru\Tactitian\Tests\CommandBusTestCase;
use Necronru\Tactitian\Tests\Dto\Awesome;
use Necronru\Tactitian\Tests\Handler\AwesomeHandler;
use Necronru\Tactitian\Tests\Message\AwesomeQuery;

class TypeCastMiddlewareTest extends CommandBusTestCase
{
    public function testTypeCast()
    {
        $classNameExtractor = new ClassNameExtractor();

        $locator = new InMemoryLocator([
            AwesomeQuery::class => new AwesomeHandler()
        ]);

        $inflector = new InvokeInflector();

        $queryBus = new CommandBus([
            new TypeCastMessageMiddleware($classNameExtractor, $locator, $inflector),
            new CommandHandlerMiddleware($classNameExtractor, $locator, $inflector),
        ]);

        $json = $queryBus->handle(CastTo::customType(new AwesomeQuery(), 'json'));
        $this->assertJson($json);
        $this->assertJsonStringEqualsJsonString('{"id": 1}', $json);

        $array = $queryBus->handle(CastTo::array(new AwesomeQuery()));
        $this->assertEquals(["id" => 1], $array);

        $int = $queryBus->handle(CastTo::int(new AwesomeQuery()));
        $this->assertEquals(1, $int);

        $string = $queryBus->handle(CastTo::string(new AwesomeQuery()));
        $this->assertEquals("1", $string);

        $bool = $queryBus->handle(CastTo::bool(new AwesomeQuery()));
        $this->assertEquals(true, $bool);

        $object = $queryBus->handle(CastTo::object(new AwesomeQuery(), Awesome::class));
        $this->assertInstanceOf(Awesome::class, $object);

        $awesome = new Awesome();
        $awesome->id = 1;
        $this->assertEquals($object, $awesome);

        $arrayOf = $queryBus->handle(CastTo::arrayOf(new AwesomeQuery(), Awesome::class));
        $this->assertInstanceOf(Awesome::class, $arrayOf[0]);
        $this->assertEquals($arrayOf[0], $awesome);
    }
}