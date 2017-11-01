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
    protected $context = [];

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    public function hasContext(string $key): bool
    {
        return array_key_exists($key, $this->context);
    }

    public function getContext(string $key = null)
    {
        return ($key && $this->hasContext($key)) ? $this->context[$key] : $this->context;

    }

}