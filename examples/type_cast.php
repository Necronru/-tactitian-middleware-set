<?php require_once __DIR__ . '/../vendor/autoload.php';

use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\InvokeInflector;
use Necronru\Tactitian\Middleware\TypeCast\CastTo;
use Necronru\Tactitian\Middleware\TypeCast\TypeCastMessageMiddleware;

class Awesome
{
    public $id = 1;

    public function __toString()
    {
        return (string)$this->id;
    }
}

class GetAwesomeQuery
{
}

class GetAwesomeHandler
{

    public function __invoke(GetAwesomeQuery $query)
    {
        return [
            'id' => 1,
        ];
    }

    public function handleAsArray(GetAwesomeQuery $query)
    {
        return $this->__invoke($query);
    }

    public function handleAsArrayOf($query, $className)
    {
        return [$this->handleAsObject($query, $className)];
    }

    public function handleAsObject(GetAwesomeQuery $query, $className)
    {
        if ($className === Awesome::class) {
            $awesome = new Awesome();
            $awesome->id = 1;

            return $awesome;
        }

        // throw unknown className exception or do something.

    }

    public function handleAsBool(GetAwesomeQuery $query)
    {
        // do some logic if you need
        return (bool) $this->__invoke($query);
    }

    public function handleAsInt(GetAwesomeQuery $query)
    {
        // do some logic if you need
        return (int) $this->__invoke($query);
    }

    public function handleAsFloat(GetAwesomeQuery $query)
    {
        // do some logic if you need
        return (float) $this->__invoke($query);
    }

    public function handleAsString(GetAwesomeQuery $query)
    {
        // do some logic if you need
        return (string) $this->handleAsObject($query, Awesome::class);
    }

    public function handleAsJson(GetAwesomeQuery $query)
    {
        return json_encode($this->handleAsArray($query));
    }
}

$classNameExtractor = new ClassNameExtractor();
$locator = new InMemoryLocator([
    GetAwesomeQuery::class => new GetAwesomeHandler(),
]);
$inflector = new InvokeInflector();

$queryBus = new CommandBus([

    // ...

    // handle messages implements ITypeCastableQuery
    new TypeCastMessageMiddleware($classNameExtractor, $locator, $inflector),

    new CommandHandlerMiddleware($classNameExtractor, $locator, $inflector),
]);

var_dump(
    $queryBus->handle(CastTo::customType(new GetAwesomeQuery(), 'json')),
    $queryBus->handle(CastTo::array(new GetAwesomeQuery())),
    $queryBus->handle(CastTo::int(new GetAwesomeQuery())),
    $queryBus->handle(CastTo::string(new GetAwesomeQuery())),
    $queryBus->handle(CastTo::bool(new GetAwesomeQuery())),
    $queryBus->handle(CastTo::object(new GetAwesomeQuery(), Awesome::class)),
    $queryBus->handle(CastTo::arrayOf(new GetAwesomeQuery(), Awesome::class))
);