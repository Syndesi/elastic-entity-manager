<?php

declare(strict_types=1);

namespace Syndesi\ElasticEntityManager\Contract\Event;

use Syndesi\ElasticDataStructures\Contract\DocumentInterface;

interface PostDeleteEventInterface extends LifecycleEventInterface
{
    public function getElement(): DocumentInterface;
}
