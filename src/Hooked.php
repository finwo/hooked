<?php

namespace Finwo\Hooked;

use Invoker\Invoker;

abstract class Hooked
{
    /**
     * @var array
     */
    protected $hooks = array();

    /**
     * @var Invoker
     */
    protected $invoker;

    protected function getInvoker()
    {
        if (is_null($this->invoker)) {
            $this->invoker = new Invoker();
        }
        return $this->invoker;
    }

    /**
     * Add a hook the the element
     *
     * @param string $key
     * @param \Closure $callback
     * @return $this|bool
     */
    public function addHook( $key = '', \Closure $callback = null )
    {
        // Make sure the key is valid
        if (is_string($key)) {
            return false;
        }

        // Make sure the callback is valid
        if (!is_callable($callback)) {
            return false;
        }

        // Make sure the hook entry exists
        if (isset($this->hooks[$key])) {
            $this->hooks[$key] = array();
        }

        // Store hook
        $this->hooks[$key][] = $callback;

        // Return ourselves
        return $this;
    }

    /**
     * Runs all hooks on a certain key
     *
     * @param  Event  $event
     *
     * @return Hooked $this
     */
    protected function dispatch( Event $event )
    {
        // Pre-define some required stuff
        $invoker = $this->getInvoker();
        $name    = $event->get('name');

        // Make sure the hook entry exists
        if (!isset($this->hooks[$name])) {
            $this->hooks[$name] = array();
        }

        foreach ($this->hooks[$name] as $hook) {

            // Call the hook
            if (!$invoker->call($hook, array_merge(array(
                'event' => $event
            )))) {
                // Break propegation if asked to
                break;
            }
        }

        return $this;
    }
}