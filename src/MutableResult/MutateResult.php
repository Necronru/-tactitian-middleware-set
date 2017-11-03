<?php


namespace Necronru\Tactitian\Middleware\MutableResult;


class MutateResult implements IMutableResultQuery
{
    /**
     * @var callable[]
     */
    private $mutators = [];

    /**
     * @var mixed
     */
    private $query;

    /**
     * @param  mixed                     $query
     * @param callable[]|callable|string $mutators
     */
    public function __construct($query, $mutators)
    {
        $this->query = $query;
        $this->mutators = (array)$mutators;
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
    public function getMutators()
    {
        return $this->mutators;
    }
}