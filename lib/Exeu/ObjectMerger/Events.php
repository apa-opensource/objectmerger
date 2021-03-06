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

namespace Exeu\ObjectMerger;

/**
 * All possible objectmerger events.
 *
 * @author Jan Eichhorn <exeu65@googlemail.com>
 */
class Events
{
    /**
     * The pre merge event type.
     */
    const PRE_MERGE  = 'exeu.object_merger.events.pre_merge';

    /**
     * The post merge event type.
     */
    const POST_MERGE = 'exeu.object_merger.events.post_merge';
}
