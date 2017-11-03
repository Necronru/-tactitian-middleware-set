```php
<?php

class AwesomeMessage 
{
    /**
     * @var string
     */
    public $description;
    
    public function __construct($description)
    {
        $this->description = $description;
        
    }    
}

class AwesomeMessageHandler 
{
    public function __invoke(AwesomeMessage $message) 
    {
        return $message->description;
    }
}

class SimpleLogMiddleware implements \League\Tactician\Middleware 
{    
    /**
     * @var \Psr\Log\LoggerInterface $logger   
     */
    private $logger;
    
    public function __construct(\Psr\Log\LoggerInterface $logger) 
    {
        $this->logger = $logger;
    }
    
    public function execute($command, callable $next) 
    {
        if ($command instanceof \Necronru\Tactitian\Middleware\ContextMessage\ContextMessage && $command->hasContext('guid')) {
            $guid = $command->getContext('guid');
        } else {
            $guid = uniqid();
        }
         
        $this->logger->info('message ' . $guid .' received', [
            'className' => get_class($command), 
            'payload' => serialize($command)
        ]);
        
        $returnValue = $next($command);
        
        $this->logger->info('message ' . $guid .' processed');
        
        return $returnValue;
    }
}

$logger = new \Psr\Log\NullLogger();

$messageBus = new \League\Tactician\CommandBus([
    
    new SimpleLogMiddleware($logger),
    
    new \Necronru\Tactitian\Middleware\ContextMessage\ContextMessageMiddleware(),
    
    new \League\Tactician\Handler\CommandHandlerMiddleware(
        new \League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor(),
        new \League\Tactician\Handler\Locator\InMemoryLocator([
            AwesomeMessage::class => new AwesomeMessageHandler()
        ]),
        new \League\Tactician\Handler\MethodNameInflector\InvokeInflector()
    )
]);

$messageBus->handle(
    new \Necronru\Tactitian\Middleware\ContextMessage\ContextMessage(new AwesomeMessage('TestMessage'))
); // TestMessage 
```