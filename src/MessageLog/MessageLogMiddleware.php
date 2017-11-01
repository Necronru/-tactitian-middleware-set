<?php


namespace Necronru\Tactitian\Middleware\MessageLog;


use League\Tactician\Middleware;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class MessageLogMiddleware implements Middleware
{
    /**
     * @var IMessageSerializer
     */
    private $serializer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $level;

    /**
     * @var IGuidExtractor
     */
    private $guidExtractor;

    public function __construct(IMessageSerializer $serializer,
                                IGuidExtractor $guidExtractor,
                                LoggerInterface $logger,
                                $level = LogLevel::INFO)
    {
        $this->serializer = $serializer;
        $this->logger = $logger;
        $this->level = $level;
        $this->guidExtractor = $guidExtractor;
    }


    /**
     * @param object   $message
     * @param callable $next
     *
     * @return mixed
     * @throws \Exception
     */
    public function execute($message, callable $next)
    {
        $guid = $this->guidExtractor->getGuid($message);
        $messageClass = get_class($message);
        $messageName = array_pop(explode('\\', $messageClass));

        try {

            $this->logger->log($this->level, 'started executing message ' . $messageName, [
                'guid'         => $guid,
                'messageClass' => $messageClass,
                'payload'      => $this->serializer->serialize($message),
            ]);

            $returnValue = $next($message);

            $this->logger->log($this->level, 'message executing finished ' . $messageName, [
                'guid' => $guid,
            ]);

            return $returnValue;

        } catch (\Exception $ex) {
            $this->logger->error('message executing failed ' . $messageName, [
                'guid'        => $guid,
                'message'     => $ex->getMessage(),
                'code'        => $ex->getCode(),
                'stack_trace' => $ex->getTraceAsString(),
            ]);

            throw $ex;
        }
    }
}