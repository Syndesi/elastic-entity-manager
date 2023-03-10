<?php

declare(strict_types=1);

namespace Syndesi\ElasticEntityManager\Tests\Event;

use PHPUnit\Framework\TestCase;
use Syndesi\ElasticDataStructures\Type\Document;
use Syndesi\ElasticEntityManager\Event\DocumentPostMergeEvent;

class DocumentPostMergeEventTest extends TestCase
{
    public function testDocumentPostMergeEvent(): void
    {
        $element = new Document();
        $event = new DocumentPostMergeEvent($element);
        $this->assertSame($element, $event->getElement());
    }

    public function testElementManipulation(): void
    {
        $element = new Document();
        $element->addProperty('some', 'value');
        $event = new DocumentPostMergeEvent($element);
        $this->assertTrue($event->getElement()->hasProperty('some'));
        $event->getElement()->removeProperty('some');
        $this->assertFalse($event->getElement()->hasProperty('some'));
    }
}
