<?php

declare(strict_types=1);

namespace Syndesi\ElasticEntityManager\Event;

use Syndesi\ElasticEntityManager\Contract\Event\PostFlushEventInterface;
use Syndesi\ElasticEntityManager\Trait\StoppableEventTrait;

class PostFlushEvent implements PostFlushEventInterface
{
    use StoppableEventTrait;
}
