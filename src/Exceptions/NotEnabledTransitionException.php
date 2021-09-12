<?php

/*
 * This file is part of the Workflow package.
 *
 * (c) Mark Fluehmann mark.fluehmann@gmail.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dbizapps\Workflow\Exceptions;

use dbizapps\Workflow\Contracts\WorkflowInterface;
use dbizapps\Workflow\Exception\TransitionException;

class NotEnabledTransitionException extends TransitionException
{

    /**
     * @var 
     */
    private $transitionBlockerList;

    /**
     * Class constructor
     * 
     * @param object  $subject
     * @param string  $transition
     * @param Workflow  $workflow
     * @param array  $events
     */
    public function __construct(object $subject, string $transitionName, WorkflowInterface $workflow, TransitionBlockerList $transitionBlockerList, array $context = [])
    {
        parent::__construct($subject, $transitionName, $workflow, sprintf('Transition "%s" is not enabled for workflow "%s".', $transitionName, $workflow->getName()), $context);

        $this->transitionBlockerList = $transitionBlockerList;
    }

    public function getTransitionBlockerList()
    {
        return $this->transitionBlockerList;
    }
}

