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
 * Interface of a light weight eventdispatcher which is used for this library.
 *
 * @author Jan Eichhorn <exeu65@googlemail.com>
 */
interface EventDispatcherInterface
{
    /**
     * Adds a listener.
     *
     * @param string   $eventName
     * @param callable $listener
     */
    public function addListener($eventName, $listener);

    /**
     * Dispatches an event if there are any listeners.
     *
     * @param string $eventName
     * @param mixed  $event
     */
    public function dispatch($eventName, Event $event);

    /**
     * Checks if there are listeners registered for this event.
     *
     * @param string $eventName
     *
     * @return bool
     */
    public function hasListener($eventName);
}
