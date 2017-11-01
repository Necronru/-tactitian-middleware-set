<?php


namespace Necronru\Tactitian\Tests\Message;

class SuccessTestMessage
{
    public $id = 1;

    public $description = 'Test description';

    public $guid;

    public $message;

    public function __construct($message = 'test')
    {
        $this->guid = uniqid();
        $this->message = $message;
    }

    public function getGuid()
    {
        return $this->guid;
    }
}