<?php

declare(strict_types=1);

namespace Syndesi\ElasticEntityManager\Contract\Event;

use Syndesi\ElasticDataStructures\Contract\DocumentInterface;

interface DocumentPostDeleteEventInterface extends PostDeleteEventInterface
{
    public function getElement(): DocumentInterface;
}
