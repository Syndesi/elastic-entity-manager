<?php

declare(strict_types=1);

namespace Syndesi\ElasticEntityManager\Event;

use Syndesi\ElasticDataStructures\Contract\DocumentInterface;
use Syndesi\ElasticEntityManager\Contract\Event\DocumentPreMergeEventInterface;
use Syndesi\ElasticEntityManager\Trait\StoppableEventTrait;

class DocumentPreMergeEvent implements DocumentPreMergeEventInterface
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
