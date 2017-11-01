<?php


namespace Necronru\Tactitian\Middleware\GenericMessage;


interface IGenericMessage
{

    /**
     * @return mixed
     */
    public function getSubject();

    /**
     * @return array
     */
    public function getArguments(): array;

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasArgument(string $key): bool;

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getArgument(string $key);
}