<?php


namespace Necronru\Tactitian\Middleware\TypeCast;


use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;

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

        if (preg_match($this->getTypeOfStringRegex(), $command->getType(), $matches)) {
            $methodName = $this->getScalarHandleMethodName($matches[3]);
        }

        if (!$methodName) {
            throw new \Exception('Type ' . $command->getType() . ' is not supported.');
        }

        if (!method_exists($commandHandler, $methodName)) {
            throw new \Exception('Response of type "' . $command->getType() . '" is not supported for ' . get_class($commandHandler) . ' because method '.$methodName.' does not exists.');
        }

        return $methodName;

    }

    public function getScalarHandleMethodName($type)
    {
        return 'handleAs' . preg_replace('/[^A-Za-z0-9\-]/', '', ucwords($type, '_'));
    }

    /**
     * @return string
     */
    public function getTypeOfClassRegex(): string
    {
        return '/^(object|arrayOf)(\<)(.*)(\>)$/';
    }

    /**
     * @return string
     */
    public function getTypeOfStringRegex(): string
    {
        return '/^(custom)(\<)(.*)(\>)$/';
    }
}