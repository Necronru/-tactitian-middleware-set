<?php


namespace Necronru\Tactitian\Middleware\MutableResult;


interface IMutableResultQuery
{
    /**
     * @return mixed
     */
    public function getQuery();

    /**
     * @return callable[]
     */
    public function getMutators();

}