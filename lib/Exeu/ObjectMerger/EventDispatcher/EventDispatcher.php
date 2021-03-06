<?php
/*
 * Copyright 2014 Jan Eichhorn <exeu65@googlemail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Exeu\ObjectMerger\EventDispatcher;
use Exeu\ObjectMerger\Event\Event;

/**
 * Class EventDispatcher
 *
 * @author Jan Eichhorn <exeu65@googlemail.com>
 */
class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var array
     */
    protected $listeners = array();

    /**
     * {@inheritDoc}
     */
    public function addListener($eventName, $listener)
    {
        $this->listeners[$eventName][] = $listener;
    }

    /**
     * {@inheritDoc}
     */
    public function dispatch($eventName, Event $event)
    {
        if (!$this->hasListener($eventName)) {
            return;
        }

        foreach ($this->listeners[$eventName] as $listener) {
            $event->setEventDispatcher($this);
            call_user_func($listener, $event);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function hasListener($eventName)
    {
        return isset($this->listeners[$eventName]);
    }
}
