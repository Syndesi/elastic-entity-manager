<?php

declare(strict_types=1);

namespace Syndesi\ElasticEntityManager\Contract;

use Syndesi\ElasticDataStructures\Contract\DocumentInterface;
use Syndesi\ElasticEntityManager\Type\ActionType;

interface ActionElasticElementInterface
{
    public function getAction(): ActionType;

    public function getElement(): DocumentInterface;
}
