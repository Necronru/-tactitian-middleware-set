<?php


namespace Necronru\Tactitian\Middleware\TypeCast;


use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;
use Necronru\Tactitian\Middleware\TypeCast\ITypeCastableQuery;

class TypeCastMessageInflector implements MethodNameInflector
{
    private $scalarTypes = ['array', 'bool', 'int', 'string', 'float', 'object'];

    /**
     * @var MethodNameInflector
     */
    private $parent;

    public function __construct(MethodNameInflector $parent)
    {
        $this->parent = $parent;
    }


    /**
     * Return the method name to call on the command handler and return it.
     *
     * @param object $command
     * @param object $commandHandler
     *
     * @return string
     * @throws \Exception
     */
    public function inflect($command, $commandHandler)
    {
        if (!$command instanceof ITypeCastableQuery) {
            return $this->parent->inflect($command, $commandHandler);
        }

        $methodName = null;

        if (in_array($command->getType(), $this->scalarTypes)) {
            $methodName = $this->getScalarHandleMethodName($command->getType());
        }

        if (preg_match($this->getTypeOfClassRegex(), $command->getType(), $matches)) {
            $methodName = $this->getScalarHandleMethodName($matches[1]);
        }

        if (!$methodName) {
            throw new \Exception('Type ' . $command->getType() . ' is not supported.');
        }

        if (!method_exists($commandHandler, $methodName)) {
            throw new \Exception($command->getType() . ' is not supported for ' . get_class($commandHandler) . '.');
        }

        return $methodName;

    }

    public function getScalarHandleMethodName($type)
    {
        return 'handleAs' . ucfirst($type);
    }

    /**
     * @return string
     */
    public function getTypeOfClassRegex(): string
    {
        return '/^(object|arrayOf)(\<)(.*)(\>)$/';
    }
}