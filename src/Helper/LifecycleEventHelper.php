<?php

declare(strict_types=1);

namespace Syndesi\ElasticEntityManager\Helper;

use Syndesi\ElasticEntityManager\Contract\Event\LifecycleEventInterface;
use Syndesi\ElasticEntityManager\Event\DocumentPostCreateEvent;
use Syndesi\ElasticEntityManager\Event\DocumentPostDeleteEvent;
use Syndesi\ElasticEntityManager\Event\DocumentPostMergeEvent;
use Syndesi\ElasticEntityManager\Event\DocumentPreCreateEvent;
use Syndesi\ElasticEntityManager\Event\DocumentPreDeleteEvent;
use Syndesi\ElasticEntityManager\Event\DocumentPreMergeEvent;
use Syndesi\ElasticEntityManager\Type\ActionElasticElement;
use Syndesi\ElasticEntityManager\Type\ActionElasticElementType;
use Syndesi\ElasticEntityManager\Type\ActionType;

class LifecycleEventHelper
{
    /**
     * @return LifecycleEventInterface[]
     */
    public static function getLifecycleEventForElasticActionElement(ActionElasticElement $actionElasticElement, bool $isPre): array
    {
        $eventClasses = [
            ActionElasticElementType::DOCUMENT->name => [
                'Pre' => [
                    ActionType::CREATE->name => DocumentPreCreateEvent::class,
                    ActionType::MERGE->name => DocumentPreMergeEvent::class,
                    ActionType::DELETE->name => DocumentPreDeleteEvent::class,
                ],
                'Post' => [
                    ActionType::CREATE->name => DocumentPostCreateEvent::class,
                    ActionType::MERGE->name => DocumentPostMergeEvent::class,
                    ActionType::DELETE->name => DocumentPostDeleteEvent::class,
                ],
            ],
        ];
        $elementType = ActionElasticElementHelper::getTypeFromActionElasticElement($actionElasticElement);
        if (array_key_exists($elementType->name, $eventClasses)) {
            $eventClass = $eventClasses[$elementType->name];
            if (array_key_exists($isPre ? 'Pre' : 'Post', $eventClass)) {
                $eventClass = $eventClass[$isPre ? 'Pre' : 'Post'];
                if (array_key_exists($actionElasticElement->getAction()->name, $eventClass)) {
                    $eventClass = $eventClass[$actionElasticElement->getAction()->name];

                    return [
                        new $eventClass($actionElasticElement->getElement()),
                    ];
                }
            }
        }
        throw new \LogicException("this line can not be reached");
    }
}
