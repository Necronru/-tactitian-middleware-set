<?php


namespace Necronru\Tactitian\Middleware\ContextMessage;


interface IContextMessage
{

    /**
     * @return mixed
     */
    public function getSubject();

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasContext(string $key): bool;

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getContext(string $key = null);
}