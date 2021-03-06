<?php

namespace Tests;

use dbizapps\Workflow\Contracts\WorkflowStoreInterface;
use dbizapps\Workflow\Traits\HasWorkflow;

final class Subject implements WorkflowStoreInterface
{
    use HasWorkflow;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var int
     */
    public $value = 100;

    /**
     * @var boolean
     */
    public $blocked = true;


    public function setValue( $value )
    {
        if (! is_null($value) )
            $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}