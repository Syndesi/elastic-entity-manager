<?php

declare(strict_types=1);

namespace Syndesi\ElasticEntityManager\Event;

use Syndesi\ElasticDataStructures\Contract\DocumentInterface;
use Syndesi\ElasticEntityManager\Contract\Event\DocumentPreDeleteEventInterface;
use Syndesi\ElasticEntityManager\Trait\StoppableEventTrait;

class DocumentPreDeleteEvent implements DocumentPreDeleteEventInterface
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
