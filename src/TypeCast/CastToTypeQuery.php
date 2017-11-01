<?php


namespace Necronru\Tactitian\Middleware\TypeCast;

class CastToTypeQuery implements ITypeCastableQuery
{
    /**
     * @var
     */
    private $query;

    /**
     * @var string
     */
    private $type;

    public function __construct($query, string $type)
    {
        $this->query = $query;
        $this->type = $type;
    }


    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }


}