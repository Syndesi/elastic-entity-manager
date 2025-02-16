<?php

declare(strict_types=1);

namespace Syndesi\ElasticEntityManager\Type;

use Syndesi\ElasticDataStructures\Contract\DocumentInterface;
use Syndesi\ElasticEntityManager\Contract\ActionElasticElementInterface;

class ActionElasticElement implements ActionElasticElementInterface
{
    public function __construct(
        private readonly ActionType $actionType,
        private readonly DocumentInterface $element,
    ) {
    }

    public function getAction(): ActionType
    {
        return $this->actionType;
    }

    public function getElement(): DocumentInterface
    {
        return $this->element;
    }
}
