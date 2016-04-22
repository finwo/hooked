<?php

namespace Finwo\Hooked;

use Finwo\PropertyAccessor\PropertyAccessor;

class Event
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var array
     */
    protected $param = array();

    /**
     * @var PropertyAccessor
     */
    private $accessor;

    /**
     * Event constructor.
     * @param string $name
     * @param array $parameters
     */
    public function __construct($name = '', &$parameters = array())
    {
        $this->name  = $name;
        $this->param = &$parameters;
    }

    /**
     * @return PropertyAccessor
     */
    private function getAccessor()
    {
        if (is_null($this->accessor)) {
            $this->accessor = new PropertyAccessor(true);
        }
        return $this->accessor;
    }

    /**
     * @param $key
     * @return array|mixed|null
     * @throws \Exception
     */
    public function get($key)
    {
        // Try directly, might be the quickest way
        if (isset($this->{$key})) {
            return $this->{$key};
        }

        // Look deeper
        $accessor = $this->getAccessor();
        return $accessor->get($this, $key, '.');
    }

    public function set($key, &$value)
    {
        // Try directly, might be the quickest way
        if (isset($this->{$key})) {
            $this->{$key} = &$value;
            return $this;
        }

        // Look deeper
        $accessor = $this->getAccessor();
        $accessor->set($this, $key, $value, '.');
        return $this;
    }
}