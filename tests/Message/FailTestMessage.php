<?php


namespace Necronru\Tactitian\Tests\Message;

class FailTestMessage
{
    public $id = 1;

    public $description = 'Test description';

    public $guid;

    public function __construct()
    {
        $this->guid = uniqid();
    }

    public function getGuid()
    {
        return $this->guid;
    }
}