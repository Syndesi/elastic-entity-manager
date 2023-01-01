<?php

declare(strict_types=1);

namespace Syndesi\ElasticEntityManager\Contract\Event;

use Syndesi\ElasticDataStructures\Contract\DocumentInterface;

interface LifecycleEventInterface extends EventInterface
{
    public function getElement(): DocumentInterface;
}
