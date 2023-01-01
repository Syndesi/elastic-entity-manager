<?php

declare(strict_types=1);

namespace Syndesi\ElasticEntityManager\Tests\Helper;

use PHPUnit\Framework\TestCase;
use Syndesi\ElasticDataStructures\Type\Document;
use Syndesi\ElasticEntityManager\Event\DocumentPostCreateEvent;
use Syndesi\ElasticEntityManager\Event\DocumentPostDeleteEvent;
use Syndesi\ElasticEntityManager\Event\DocumentPostMergeEvent;
use Syndesi\ElasticEntityManager\Event\DocumentPreCreateEvent;
use Syndesi\ElasticEntityManager\Event\DocumentPreDeleteEvent;
use Syndesi\ElasticEntityManager\Event\DocumentPreMergeEvent;
use Syndesi\ElasticEntityManager\Helper\LifecycleEventHelper;
use Syndesi\ElasticEntityManager\Type\ActionElasticElement;
use Syndesi\ElasticEntityManager\Type\ActionType;

class LifecycleEventHandlerTest extends TestCase
{
    public function provideTestCases()
    {
        return [
            [
                new ActionElasticElement(ActionType::CREATE, new Document()),
                true,
                [
                    DocumentPreCreateEvent::class,
                ],
            ],
            [
                new ActionElasticElement(ActionType::CREATE, new Document()),
                false,
                [
                    DocumentPostCreateEvent::class,
                ],
            ],
            [
                new ActionElasticElement(ActionType::MERGE, new Document()),
                true,
                [
                    DocumentPreMergeEvent::class,
                ],
            ],
            [
                new ActionElasticElement(ActionType::MERGE, new Document()),
                false,
                [
                    DocumentPostMergeEvent::class,
                ],
            ],
            [
                new ActionElasticElement(ActionType::DELETE, new Document()),
                true,
                [
                    DocumentPreDeleteEvent::class,
                ],
            ],
            [
                new ActionElasticElement(ActionType::DELETE, new Document()),
                false,
                [
                    DocumentPostDeleteEvent::class,
                ],
            ],
        ];
    }

    /**
     * @dataProvider provideTestCases
     */
    public function testCases(ActionElasticElement $actionElasticElement, bool $isPre, array $expectedEvents): void
    {
        $actualEvents = LifecycleEventHelper::getLifecycleEventForElasticActionElement($actionElasticElement, $isPre);
        $this->assertSame(count($expectedEvents), count($actualEvents));
        foreach ($expectedEvents as $i => $expectedEvent) {
            $this->assertInstanceOf($expectedEvent, $actualEvents[$i]);
        }
    }
}
