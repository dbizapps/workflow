<?php

use dbizapps\Workflow\Models\Workflow;

return [
    'WF_config' => [
        'supports' => stdClass::class,
        'states' => ['a', 'b', 'c', 'd', 'e', 'f', 'g'],
        'transitions' => [
            't1' => [
                'from' => 'a',
                'to' => ['b', 'c'],
            ],
            't2' => [
                'from' => ['b', 'c'],
                'to' => 'd',
                'conditions' => ['Value gt 100', function ($subject) { return $subject->value >= 100; }],
            ],
            't3' => [
                'from' => 'd',
                'to' => 'e',
            ],
            't4' => [
                'from' => 'd',
                'to' => 'f',
            ],
            't5' => [
                'from' => 'e',
                'to' => 'g',
            ],
            't6' => [
                'from' => 'f',
                'to' => 'g',
            ],
        ],
        'events' => [
            Workflow::ENTER_EVENT,
        ],
        'dispatcher' => null,
        'logger' => null,
    ],
];