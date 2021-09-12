<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\EventDispatcher;
use dbizapps\Workflow\Contracts\EventDispatcherInterface;
use dbizapps\Workflow\Events\Event;
use dbizapps\Workflow\Events\TransitionEvent;
use dbizapps\Workflow\Exceptions\NotEnabledTransitionException;
use dbizapps\Workflow\Exceptions\UndefinedTransitionException;
use dbizapps\Workflow\Models\Definition;
use dbizapps\Workflow\Models\Transition;
use dbizapps\Workflow\Models\TransitionBlocker;
use dbizapps\Workflow\Models\Workflow;


class WorkflowEventTest extends TestCase
{
    use WorkflowBuilderTrait;

    public function testApplyWithEventDispatcher()
    {
        $subject = new Subject();

        $eventDispatcher = new EventDispatcherMock();

        $definition = $this->createComplexWorkflowDefinition();

        $workflow = new Workflow($definition, $eventDispatcher);
  
        $eventNameExpected = [
            'workflow.leave',
            'workflow.WF_complex.leave',
            'workflow.WF_complex.leave.a',
            'workflow.transition',
            'workflow.WF_complex.transition',
            'workflow.WF_complex.transition.t1',
            'workflow.enter',
            'workflow.WF_complex.enter',
            'workflow.WF_complex.enter.b',
            'workflow.WF_complex.enter.c',
            'workflow.entered',
            'workflow.WF_complex.entered',
            'workflow.WF_complex.entered.b',
            'workflow.WF_complex.entered.c',
        ];

        $workflow->apply($subject, 't1');

        $this->assertSame($eventNameExpected, $eventDispatcher->dispatchedEvents);
    }


    public function testApplyDispatchesWithSupressedEvents()
    {
        $subject = new Subject();

        $transitions[] = new Transition('a-b', 'a', 'b');
        $transitions[] = new Transition('a-c', 'a', 'c');

        $definition =  new Definition([
            'name' => 'WF', 
            'states' => ['a', 'b', 'c'], 
            'initial' => 'a',
            'transitions' => $transitions, 
            'supports' => 'Subject'
        ]);

        $eventDispatcher = new EventDispatcherMock();

        $workflow = new Workflow($definition, $eventDispatcher);

        $eventNameExpected = [
            'workflow.transition',
            'workflow.WF.transition',
            'workflow.WF.transition.a-b',
        ];

        $workflow->apply($subject, 'a-b', [
            Workflow::LEAVE_EVENT,
            Workflow::ENTER_EVENT,
            Workflow::ENTERED_EVENT,
        ]);

        $this->assertSame($eventNameExpected, $eventDispatcher->dispatchedEvents);
    }


    public function testApplyDispatchesNoEventsWhenSpecifiedByDefinition()
    {
        $subject = new Subject();

        $transitions[] = new Transition('a-b', 'a', 'b');
        $transitions[] = new Transition('a-c', 'a', 'c');

        $definition =  new Definition([
            'name' => 'WF', 
            'states' => ['a', 'b', 'c'], 
            'initial' => 'a',
            'transitions' => $transitions,
            'events' => [],
        ]);

        $eventDispatcher = new EventDispatcherMock();

        $workflow = new Workflow($definition, $eventDispatcher);

        $eventNameExpected = [
        ];

        $workflow->apply($subject, 'a-b');

        $this->assertSame($eventNameExpected, $eventDispatcher->dispatchedEvents);
    }


    public function testApplyOnlyDispatchesEventsThatHaveBeenSpecifiedByDefinition()
    {
        $subject = new Subject();

        $transitions[] = new Transition('a-b', 'a', 'b');
        $transitions[] = new Transition('a-c', 'a', 'c');

        $definition =  new Definition([
            'name' => 'WF', 
            'states' => ['a', 'b', 'c'], 
            'initial' => 'a',
            'transitions' => $transitions,
            'events' => [Workflow::ENTERED_EVENT],
        ]);

        $eventDispatcher = new EventDispatcherMock();

        $workflow = new Workflow($definition, $eventDispatcher);

        $eventNameExpected = [
            'workflow.entered',
            'workflow.WF.entered',
            'workflow.WF.entered.b',
        ];

        $workflow->apply($subject, 'a-b');

        $this->assertSame($eventNameExpected, $eventDispatcher->dispatchedEvents);
    }


    public function testEventWorkflowName()
    {
        $subject = new Subject();

        $definition = $this->createComplexWorkflowDefinition();

        $dispatcher = new EventDispatcher();
        
        $workflow = new Workflow($definition, $dispatcher);

        $assertWorkflowName = function (Event $event) use ($definition) {
            $this->assertEquals($definition->getName(), $event->getWorkflowName());
        };

        $eventNames = [
            Workflow::LEAVE_EVENT,
            Workflow::TRANSITION_EVENT,
            Workflow::ENTER_EVENT,
            Workflow::ENTERED_EVENT,
        ];

        foreach ($eventNames as $eventName) {
            $dispatcher->addListener($eventName, $assertWorkflowName);
        }

        $workflow->apply($subject, 't1');
    }
}


class EventDispatcherMock implements EventDispatcherInterface
{
    public $dispatchedEvents = [];

    public function dispatch($event, string $eventName = null): object
    {
        $this->dispatchedEvents[] = $eventName;

        return $event;
    }
}
