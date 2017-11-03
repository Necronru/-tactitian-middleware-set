<?php


namespace Necronru\Tactitian\Middleware\ContextMessage;


class ContextMessage implements IContextMessage
{
    use ContextMessageTrait;

    public function __construct($subject, array $arguments = [])
    {
        $this->subject = $subject;
        $this->context = $arguments;
    }
}