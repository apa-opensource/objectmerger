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

use Exeu\ObjectMerger\Accessor\PropertyAccessorRegistry;
use Exeu\ObjectMerger\Event\MergeEvent;
use Exeu\ObjectMerger\EventDispatcher\EventDispatcher;
use Metadata\MetadataFactory;
use Exeu\ObjectMerger\Metadata\PropertyMetadata;

/**
 * The GraphWalker walks through every property of the object
 * and calls on each comparable property the visitor.
 *
 * @author Jan Eichhorn <exeu65@googlemail.com>
 */
class GraphWalker
{
    /**
     * @var array
     */
    protected $visitedObjects = array();

    /**
     * @var MetadataFactory
     */
    protected $metadataFactory;

    /**
     * @var MergingVisitor
     */
    protected $visitor;

    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @var PropertyAccessorRegistryInterface
     */
    protected $propertyAccessorRegistry;

    /**
     * @var MergeHandlerRegistryInterface
     */
    protected $mergeHandlerRegistry;

    /**
     * Constructor.
     *
     * @param MetadataFactory                   $metadataFactory
     * @param PropertyAccessorRegistryInterface $propertyAccessorRegistry
     * @param MergeHandlerRegistryInterface     $mergeHandlerRegistry
     * @param EventDispatcher                   $dispatcher
     */
    public function __construct(
        MetadataFactory $metadataFactory,
        PropertyAccessorRegistryInterface $propertyAccessorRegistry,
        MergeHandlerRegistryInterface $mergeHandlerRegistry,
        EventDispatcher $dispatcher
    )
    {
        $this->metadataFactory          = $metadataFactory;
        $this->eventDispatcher          = $dispatcher;
        $this->propertyAccessorRegistry = $propertyAccessorRegistry;
        $this->mergeHandlerRegistry     = $mergeHandlerRegistry;
        $this->visitor                  = new MergingVisitor();
    }

    /**
     * Accepts an objectpair and going through its properties for calling the visitor on each
     * comparable property.
     *
     * @param object $mergeFrom Sourceobject
     * @param object $mergeTo   Targetobject
     */
    public function accept($mergeFrom, $mergeTo)
    {
        // No object is passed either for mergeFrom or mergeTo.
        if (!is_object($mergeFrom) || !is_object($mergeTo)) {
            return;
        }

        $class = get_class($mergeFrom);
        if (!$mergeTo instanceof $class) {
            return;
        }

        // If the object is not visited.
        if (isset($this->visitedObjects[spl_object_hash($mergeFrom)])) {
            return;
        }

        $classMetadata = $this->metadataFactory->getMetadataForClass(get_class($mergeFrom));

        // Preventing the object to be visited again.
        $this->visitedObjects[spl_object_hash($mergeFrom)] = true;

        // Preparing a new ExecutionContext.
        $executionContext = new MergeContext($classMetadata, $this, $mergeFrom, $mergeTo);

        // Dispatching the premerge event.
        $this->eventDispatcher->dispatch(
            Events::PRE_MERGE,
            new MergeEvent(MergeEvent::TYPE_PRE, $executionContext)
        );

        foreach ($classMetadata->propertyMetadata as $comparableProperty) {
            /** @var PropertyMetadata $comparableProperty */
            switch ($comparableProperty->type) {
                case 'string':
                case 'object':
                case 'boolean':
                case 'Collection':
                    // calls a type specified merge method on the visitor.
                    $this->visitor->{'merge' . ucfirst($comparableProperty->type)}(
                        $comparableProperty,
                        $executionContext
                    );
                    break;
                default:
                    // If the type is not one of the default types, try to merge this property by a handler.
                    try {
                        $this->visitor->mergeByHandler($comparableProperty, $executionContext);
                    } catch (\Exception $e) { }
                    break;
            }
        }

        // Dispatching the postmerge event.
        $this->eventDispatcher->dispatch(
            Events::POST_MERGE,
            new MergeEvent(MergeEvent::TYPE_POST, $executionContext)
        );
    }

    /**
     * Gets the mergevisitor.
     *
     * @return MergingVisitor
     */
    public function getVisitor()
    {
        return $this->visitor;
    }

    /**
     * Gets MetadataFactory
     *
     * @return MetadataFactory
     */
    public function getMetadataFactory()
    {
        return $this->metadataFactory;
    }

    /**
     * Gets PropertyAccessorRegistry
     *
     * @return PropertyAccessorRegistry
     */
    public function getPropertyAccessorRegistry()
    {
        return $this->propertyAccessorRegistry;
    }

    /**
     * Gets MergeHandlerRegistry
     *
     * @return MergeHandlerRegistryInterface
     */
    public function getMergeHandlerRegistry()
    {
        return $this->mergeHandlerRegistry;
    }
}
