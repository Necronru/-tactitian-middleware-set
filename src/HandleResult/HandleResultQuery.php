<?php


namespace Necronru\Tactitian\Middleware\HandleResult;


class HandleResultQuery implements IHandleResultQuery
{
    /**
     * @var callable[]
     */
    private $callbacks = [];

    /**
     * @var mixed
     */
    private $query;

    /**
     * @param  mixed                     $query
     * @param callable[]|callable|string $callbacks
     */
    public function __construct($query, $callbacks)
    {
        foreach ((array) $callbacks as $callable) {
            $this->addCallback($callable);
        };

        $this->query = $query;
    }

    /**
     * @inheritdoc
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @inheritdoc
     */
    public function getCallbacks()
    {
        return $this->callbacks;
    }

    protected function addCallback(callable $callable)
    {
        $this->callbacks[] = $callable;
    }
}