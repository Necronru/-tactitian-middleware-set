<?php


namespace Necronru\Tactitian\Middleware\HandleResult;


interface IHandleResultQuery
{
    /**
     * @return mixed
     */
    public function getQuery();

    /**
     * @return callable[]
     */
    public function getCallbacks();

}