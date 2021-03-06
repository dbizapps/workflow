<?php

/*
 * This file is part of the Workflow package.
 *
 * (c) Mark Fluehmann mark.fluehmann@gmail.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dbizapps\Workflow\Contracts;

use dbizapps\Workflow\State;
use dbizapps\Workflow\Listener;
use dbizapps\Workflow\Transition;

interface DefinitionInterface
{

    /**
     * Get Workflow Name
     * 
     * @return string
     */
    public function getName();

    /**
     * Get Workflow Description
     * 
     * @return string|null
     */
    public function getDescription();

    /**
     * Add Workflow State
     * 
     * @param  State  $state 
     * @return void
     */
    public function addState(State $state);

    /**
     * Get Workflow States
     * 
     * @param string  $state
     * @return string
     */
    public function getStates($state = null);

    /**
     * Get Initial Workflow State
     * 
     * @return array
     */
    public function getInitial();

    /**
     * Add Workflow Transition
     * 
     * @param  Transition  $transition 
     * @return void
     */
    public function addTransition(Transition $transition);

    /**
     * Get Workflow Transitions
     * 
     * @param string  $transition
     * @return string
     */
    public function getTransitions($transition = null);

    /**
     * Get Workflow Events
     * 
     * @return string
     */
    public function getEvents();

    /**
     * Add Listener
     * 
     * @param  Listener  $subscriber 
     * @return void
     */
    public function addListener(Listener $subscriber);

    /**
     * Get Workflow Subscribers
     * 
     * @return string
     */
    public function getListeners();

}