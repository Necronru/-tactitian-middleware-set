<?php


namespace Necronru\Tactitian\Tests\Handler;


use Necronru\Tactitian\Tests\Message\SuccessTestMessage;

class SuccessTestHandler
{
    public function __invoke(SuccessTestMessage $message)
    {
        return $message->message;
    }


}