<?php


namespace Necronru\Tactitian\Middleware\MutableResult;


use League\Tactician\Middleware;
use Psr\Container\ContainerInterface;

class MutableResultMiddleware implements Middleware
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param object   $command
     * @param callable $next
     *
     * @return mixed
     * @throws \Exception
     */
    public function execute($command, callable $next)
    {
        if ($command instanceof IMutableResultQuery) {
            $callbacks = $command->getMutators();

            $returnValue = $next($command->getQuery());

            foreach ($callbacks as $key => $fn) {

                if (is_string($fn)) {

                    if (!function_exists($fn) && $this->container->has($fn)) {
                        $fn = $this->container->get($fn);
                    }

                    $returnValue = $fn($returnValue, $command);
                    continue;
                }


                if (is_callable($fn)) {
                    $returnValue = $fn($returnValue, $command);
                    continue;
                }

                throw new MutableResultMiddlewareException('Chain member #' . $key . ' is not callable.');
            }

            return $returnValue;
        }

        return $next($command);
    }
}