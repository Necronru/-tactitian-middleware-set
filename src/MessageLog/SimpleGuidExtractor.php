<?php


namespace Necronru\Tactitian\Middleware\MessageLog;


class SimpleGuidExtractor implements IGuidExtractor
{
    public function getGuid($message): ?string
    {
        if ($message instanceof IGuidableMessage || is_callable([$message, 'getGuid'])) {
            return $message->getGuid();
        }

        return null;
    }
}