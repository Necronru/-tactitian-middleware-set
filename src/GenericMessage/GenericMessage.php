<?php


namespace Necronru\Tactitian\Middleware\GenericMessage;


class GenericMessage implements IGenericMessage
{
    use GenericMessageTrait;

    public function __construct($subject, array $arguments = [])
    {
        $this->subject = $subject;
        $this->arguments = $arguments;
    }
}