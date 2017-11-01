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
        $this->query = $query;
        $this->callbacks = (array)$callbacks;
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
}