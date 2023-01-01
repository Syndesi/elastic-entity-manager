<?php

declare(strict_types=1);

namespace Syndesi\ElasticEntityManager\Tests\Event;

use PHPUnit\Framework\TestCase;
use Syndesi\ElasticDataStructures\Type\Document;
use Syndesi\ElasticEntityManager\Event\DocumentPostDeleteEvent;

class DocumentPostDeleteEventTest extends TestCase
{
    public function testDocumentPostDeleteEvent(): void
    {
        $element = new Document();
        $event = new DocumentPostDeleteEvent($element);
        $this->assertSame($element, $event->getElement());
    }

    public function testElementManipulation(): void
    {
        $element = new Document();
        $element->addProperty('some', 'property');
        $event = new DocumentPostDeleteEvent($element);
        $this->assertTrue($event->getElement()->hasProperty('some'));
        $event->getElement()->removeProperty('some');
        $this->assertFalse($event->getElement()->hasProperty('some'));
    }
}
