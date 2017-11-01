<?php


namespace Necronru\Tactitian\Tests\HandleResult;


class TestModifer
{
    public function __invoke($returnValue, $message)
    {
        return $returnValue . __FUNCTION__;
    }


}