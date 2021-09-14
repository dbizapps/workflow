<?php

/*
 * This file is part of the Workflow package.
 *
 * (c) Mark Fluehmann mark.fluehmann@gmail.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dbizapps\Workflow\Events;

use Psr\EventDispatcher\StoppableEventInterface;
use dbizapps\Workflow\Contracts\WorkflowInterface;
use dbizapps\Workflow\Transition;

class Event implements StoppableEventInterface
{
    /**
     * @var bool
     */
    private $propagationStopped = false;

    /**
     * @var object
     */
    protected $subject;

    /**
     * @var array
     */
    protected $transition;

    /**
     * @var array
     */
    protected $workflow;


    /**
     * Class constructor
     * 
     * @param object  $subject
     * @param Transition  $subject
     * @param WorkflowInterface  $workflow
     * @param array  $attributes
     */
    public function __construct( object $subject, Transition $transition = null, WorkflowInterface $workflow = null )
    {
        $this->subject = $subject;

        $this->transition = $transition;

        $this->workflow = $workflow;
    }


    /**
     * Evaluates if progagation has been stopped
     */
    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }


    /**
     * Stops the propagation of the event to further event listeners.
     *
     * If multiple event listeners are connected to the same event, no
     * further event listener will be triggered once any trigger calls
     * stopPropagation().
     */
    public function stopPropagation()
    {
        $this->propagationStopped = true;
    }


    /**
     * Get Subject
     * 
     * @return object
     */
    public function getSubject()
    {
        return $this->subject;
    }


    /**
     * Get Transition
     * 
     * @return Transition
     */
    public function getTransition()
    {
        return $this->transition;
    }


    /**
     * Get Workflow
     * 
     * @return Workflow
     */
    public function getWorkflow()
    {
        return $this->workflow;
    }


    /**
     * Get Workflow Name
     * 
     * @return string
     */
    public function getWorkflowName()
    {
        return $this->workflow->getName();
    }

}
