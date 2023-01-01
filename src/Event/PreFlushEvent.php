<?php

declare(strict_types=1);

namespace Syndesi\ElasticEntityManager\Event;

use Syndesi\ElasticEntityManager\Contract\Event\PreFlushEventInterface;
use Syndesi\ElasticEntityManager\Trait\StoppableEventTrait;

class PreFlushEvent implements PreFlushEventInterface
{
    use StoppableEventTrait;
}
