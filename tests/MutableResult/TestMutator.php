<?php


namespace Necronru\Tactitian\Tests\MutableResult;


class TestMutator
{
    public function __invoke($returnValue, $message)
    {
        return $returnValue . __FUNCTION__;
    }


}