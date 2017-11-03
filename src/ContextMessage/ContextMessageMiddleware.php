<?php


namespace Necronru\Tactitian\Middleware\ContextMessage;

use League\Tactician\Middleware;

class ContextMessageMiddleware implements Middleware
{
    /**
     * @param object   $command
     * @param callable $next
     *
     * @return mixed
     */
    public function execute($command, callable $next)
    {
        if ($command instanceof IContextMessage) {
            return $next($command->getSubject());
        }

        return $next($command);
    }
}