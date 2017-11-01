<?php


namespace Necronru\Tactitian\Middleware\TypeCast;


use League\Tactician\Exception\CanNotInvokeHandlerException;
use League\Tactician\Handler\CommandNameExtractor\CommandNameExtractor;
use League\Tactician\Handler\Locator\HandlerLocator;
use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;
use League\Tactician\Middleware;
use Necronru\Tactitian\Middleware\TypeCast\TypeCastMessageInflector;
use Necronru\Tactitian\Middleware\TypeCast\ITypeCastableQuery;

class TypeCastMessageMiddleware implements Middleware
{
    /**
     * @var CommandNameExtractor
     */
    private $commandNameExtractor;

    /**
     * @var HandlerLocator
     */
    private $handlerLocator;

    /**
     * @var TypeCastMessageInflector
     */
    private $methodNameInflector;

    /**
     * @param CommandNameExtractor $commandNameExtractor
     * @param HandlerLocator       $handlerLocator
     * @param MethodNameInflector  $methodNameInflector
     */
    public function __construct(
        CommandNameExtractor $commandNameExtractor,
        HandlerLocator $handlerLocator,
        MethodNameInflector $methodNameInflector
    ) {
        $this->commandNameExtractor = $commandNameExtractor;
        $this->handlerLocator = $handlerLocator;
        $this->methodNameInflector = new TypeCastMessageInflector($methodNameInflector);
    }

    /**
     * Executes a command and optionally returns a value
     *
     * @param object   $command
     * @param callable $next
     *
     * @return mixed
     *
     * @throws CanNotInvokeHandlerException
     */
    public function execute($command, callable $next)
    {
        if ($command instanceof ITypeCastableQuery) {

            $commandName = $this->commandNameExtractor->extract($command->getQuery());
            $handler = $this->handlerLocator->getHandlerForCommand($commandName);
            $methodName = $this->methodNameInflector->inflect($command, $handler);

            return $this->handleAsType($command, $handler, $methodName);
        }

        return $next($command);
    }

    protected function handleAsType(ITypeCastableQuery $query, $handler, string $methodName)
    {
        // is_callable is used here instead of method_exists, as method_exists
        // will fail when given a handler that relies on __call.
        if (!is_callable([$handler, $methodName])) {
            throw CanNotInvokeHandlerException::forCommand(
                $query,
                "Method '{$methodName}' does not exist on handler"
            );
        }

        if (preg_match($this->methodNameInflector->getTypeOfClassRegex(), $query->getType(), $matches)) {

            $className = $matches[3];
            $type      = $matches[1];

            $methodName = $this->methodNameInflector->getScalarHandleMethodName($type);

            return $handler->{$methodName}($query->getQuery(), $className);
        }

        return $handler->{$methodName}($query->getQuery());


    }

}