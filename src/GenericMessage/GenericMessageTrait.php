<?php


namespace Necronru\Tactitian\Middleware\GenericMessage;


trait GenericMessageTrait
{
    /**
     * @var mixed
     */
    protected $subject;

    /**
     * @var array
     */
    protected $arguments = [];

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function hasArgument(string $key): bool
    {
        return array_key_exists($key, $this->getArguments());
    }

    public function getArgument(string $key)
    {
        return $this->hasArgument($key) ? $this->arguments[$key] : null;

    }

}