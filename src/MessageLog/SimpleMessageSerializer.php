<?php


namespace Necronru\Tactitian\Middleware\MessageLog;


class SimpleMessageSerializer implements IMessageSerializer
{

    public function serialize($message)
    {
        return serialize($message);
    }
}