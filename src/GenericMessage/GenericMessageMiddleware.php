<?php


namespace Necronru\Tactitian\Middleware\GenericMessage;

use League\Tactician\Middleware;

class GenericMessageMiddleware implements Middleware
{
    /**
     * @param object   $command
     * @param callable $next
     *
     * @return mixed
     */
    public function execute($command, callable $next)
    {
        if ($command instanceof IGenericMessage) {
            return $next($command->getSubject());
        }

        return $next($command);
    }
}