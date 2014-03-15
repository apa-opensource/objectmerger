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

namespace Exeu\ObjectMerger\Test;

use Doctrine\Common\Collections\ArrayCollection;
use Exeu\ObjectMerger\ObjectMerger;

class ComparsionTest extends \PHPUnit_Framework_TestCase
{
    public function testX()
    {
        $bla = new ObjectMerger();

        $objectA = new ObjectA();
        $objectB = new ObjectB();
        $objectC = new ObjectB();
        $testA   = new SubObject();

        $testA->setFullname('HansImWald');
        $testA->setIgnored(false);

        $objectA->setId(1);
        $objectA->setName('Jan');
        $objectA->setStreet('blubb');

        $objectB->setId(12);
        $objectB->setFullname('Jhon');
        $objectB->setIgnored(false);

        $objectC->setId(13);
        $objectC->setFullname('Jhan');
        $objectC->setIgnored(true);

        $coll = new ArrayCollection();
        $coll->add($objectB);
        $coll->add($objectC);

        $objectA->setFriends($coll);
        $objectA->setObj($testA);



        $objectAA = new ObjectA();
        $objectBB = new ObjectB();
        $objectCC = new ObjectB();
        $testAA   = new SubObject();

        $testAA->setFullname('HansWurst');
        $testAA->setIgnored(true);

        $objectAA->setId(1);
        $objectAA->setName('Jan22');
        $objectAA->setStreet('blabb');

        $objectBB->setId(12);
        $objectBB->setFullname('Jhon2');
        $objectBB->setIgnored(true);

        $objectCC->setId(13);
        $objectCC->setFullname('Jhan');
        $objectCC->setIgnored(false);

        $coll2 = new ArrayCollection();
        $coll2->add($objectBB);
        $coll2->add($objectCC);

        $objectAA->setFriends($coll2);
        $objectAA->setObj($testAA);

        var_dump($objectAA);
        foreach ($objectAA->getFriends() as $friend) {
            var_dump($friend);
        }

        $bla->merge($objectA, $objectAA);

        foreach ($objectAA->getFriends() as $friend) {
            var_dump($friend);
        }
        var_dump($objectAA);
    }
} 