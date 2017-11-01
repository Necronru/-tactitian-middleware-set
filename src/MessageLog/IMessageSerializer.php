<?php


namespace Necronru\Tactitian\Middleware\MessageLog;


interface IMessageSerializer
{
    public function serialize($message);

}