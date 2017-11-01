<?php


namespace Necronru\Tactitian\Tests\Handler;


use Necronru\Tactitian\Tests\Message\FailTestMessage;

class FailTestHandler
{
    public function __invoke(FailTestMessage $message)
    {
        throw new \Exception('Test exception');
    }


}