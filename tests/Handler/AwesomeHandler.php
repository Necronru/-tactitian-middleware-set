<?php


namespace Necronru\Tactitian\Tests\Handler;


use Necronru\Tactitian\Tests\Dto\Awesome;
use Necronru\Tactitian\Tests\Message\AwesomeQuery;

class AwesomeHandler
{
    public function __invoke(AwesomeQuery $message)
    {
        return ['id' => $message->id];
    }

    public function handleAsJson(AwesomeQuery $message)
    {
        return json_encode($this->__invoke($message));
    }

    public function handleAsArray(AwesomeQuery $message)
    {
        return $this->__invoke($message);
    }

    public function handleAsString(AwesomeQuery $message)
    {
        return (string)$this->__invoke($message)['id'];
    }

    public function handleAsBool(AwesomeQuery $message)
    {
        return (bool)$this->__invoke($message)['id'];
    }

    public function handleAsFloat(AwesomeQuery $message)
    {
        return (float) $this->__invoke($message)['id'];
    }

    public function handleAsInt(AwesomeQuery $message)
    {
        return (int) $this->__invoke($message)['id'];
    }

    public function handleAsObject(AwesomeQuery $message, $className)
    {
        if (Awesome::class === $className) {
            $object = new Awesome();
            $object->id = $message->id;

            return $object;
        }

        throw new \Exception('Class ' . $className . ' is not supported');
    }

    public function handleAsArrayOf(AwesomeQuery $message, $className)
    {
        return [$this->handleAsObject($message, $className)];
    }



}