<?php

declare(strict_types=1);

namespace Syndesi\ElasticEntityManager\Event;

use Syndesi\ElasticDataStructures\Contract\DocumentInterface;
use Syndesi\ElasticEntityManager\Contract\Event\DocumentPreCreateEventInterface;
use Syndesi\ElasticEntityManager\Trait\StoppableEventTrait;

class DocumentPreCreateEvent implements DocumentPreCreateEventInterface
{
    use StoppableEventTrait;

    public function __construct(private DocumentInterface $element)
    {
    }

    public function getElement(): DocumentInterface
    {
        return $this->element;
    }
}
