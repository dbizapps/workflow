<?php

/*
 * This file is part of the EventDispatcher package.
 * It's an extended version of Symfony EventDispatcher to support wildcard listeners
 *
 * (c) Mark Fluehmann mark.fluehmann@gmail.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests;

use Psr\EventDispatcher\StoppableEventInterface;
use dbizapps\Workflow\Contracts\EventDispatcherInterface;

class EventDispatcher implements EventDispatcherInterface
{

    /**
     * @var array
     */
    private $listeners = [];


    /**
     * Adds an event listener that listens on the specified events.
     *
     * @param string $event
     * @param mixed $listener
     */
    public function addListener( string $event, $listener )
    {
        if (! isset($this->listeners[$event]) )
            $this->listeners[$event] = [];

        $this->listeners[$event][] = $listener;
    }


    /**
     * Removes an event listener from the specified events.
     * 
     * @param string $event
     * @param mixed $listener
     */
    public function removeListener( string $event, $listener )
    {
        if ( empty($this->listeners[$event]) )
            return;
        
        unset($this->listeners[$event][$listener]);
    }   


    /**
     * Dispatch event 
     * 
     * @param object  $event
     * @param string  $eventName
     */
    public function dispatch(object $event, string $eventName = null)
    {
        $eventName = $eventName ?? get_class($event);

        $listeners = $this->getListeners( $eventName );

        if ( $listeners )
            $this->callListeners( $listeners, $eventName, $event );

        return $event;
    }


    /**
     * Gets the listeners of a specific event or all listeners sorted by descending priority.
     *
     * @param string  $event
     * @return array The event listeners for the specified event, or all event listeners by event name
     */
    public function getListeners( string $event = null )
    {
        if (! is_null($event) && isset($this->listeners[$event]) )
            return $this->listeners[$event];

        $this->listeners;
    }


    /**
     * Checks whether an event has any registered listeners.
     *
     * @param string $event
     * @return bool true if the specified event has any listeners, false otherwise
     */
    public function hasListeners( string $event = null )
    {
        if (! is_null($event) )
            return (!empty($this->listeners[$event]) );

        return false;
    }


    /**
     * Triggers the listeners of an event.
     *
     * This method can be overridden to add functionality that is executed
     * for each listener.
     *
     * @param callable[] $listeners The event listeners
     * @param string     $eventName The name of the event to dispatch
     * @param object     $event     The event object to pass to the event handlers/listeners
     */
    private function callListeners(iterable $listeners, string $eventName, object $event)
    {
        $stoppable = $event instanceof StoppableEventInterface;

        foreach ($listeners as $listener) {

            if ( $stoppable && $event->isPropagationStopped() )
                break;

            $listener($event, $eventName, $this);
        }
    }
}