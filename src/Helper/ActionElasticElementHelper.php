<?php

declare(strict_types=1);

namespace Syndesi\ElasticEntityManager\Helper;

use Syndesi\ElasticEntityManager\Type\ActionElasticElement;
use Syndesi\ElasticEntityManager\Type\ActionElasticElementType;

class ActionElasticElementHelper
{
    /**
     * @psalm-suppress InvalidReturnType
     */
    public static function getTypeFromActionElasticElement(ActionElasticElement $_): ActionElasticElementType
    {
        return ActionElasticElementType::DOCUMENT;
    }
}
