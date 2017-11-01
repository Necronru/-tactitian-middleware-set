<?php


namespace Necronru\Tactitian\Middleware\MessageLog;


interface IGuidableMessage
{
    public function getGuid(): string;

}