<?php

declare(strict_types=1);

namespace Syndesi\ElasticEntityManager\Tests\Event;

use PHPUnit\Framework\TestCase;
use Syndesi\ElasticDataStructures\Type\Document;
use Syndesi\ElasticEntityManager\Event\DocumentPreDeleteEvent;

class DocumentPreDeleteEventTest extends TestCase
{
    public function testDocumentPreDeleteEvent(): void
    {
        $element = new Document();
        $event = new DocumentPreDeleteEvent($element);
        $this->assertSame($element, $event->getElement());
    }

    public function testElementManipulation(): void
    {
        $element = new Document();
        $element->addProperty('some', 'value');
        $event = new DocumentPreDeleteEvent($element);
        $this->assertTrue($event->getElement()->hasProperty('some'));
        $event->getElement()->removeProperty('some');
        $this->assertFalse($event->getElement()->hasProperty('some'));
    }
}
