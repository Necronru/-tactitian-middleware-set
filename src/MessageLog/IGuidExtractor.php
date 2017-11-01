<?php


namespace Necronru\Tactitian\Middleware\MessageLog;


interface IGuidExtractor
{
    public function getGuid($message): ?string;

}