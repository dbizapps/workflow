<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dbizapps\Workflow\Contracts;

interface EventDispatcherInterface
{
    /**
     * Dispatch event 
     * 
     * @param object  $event
     * @param string  $eventName
     */
    public function dispatch(object $event, string $eventName = null);

}