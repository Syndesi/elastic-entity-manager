<?php

declare(strict_types=1);

namespace Syndesi\ElasticEntityManager\Helper;

use Syndesi\ElasticDataStructures\Contract\DocumentInterface;
use Syndesi\ElasticEntityManager\Type\ActionElasticElement;
use Syndesi\ElasticEntityManager\Type\ActionElasticElementType;

class ActionElasticElementHelper
{
    /**
     * @psalm-suppress InvalidReturnType
     */
    public static function getTypeFromActionElasticElement(ActionElasticElement $actionElasticElement): ActionElasticElementType
    {
        $element = $actionElasticElement->getElement();
        if ($element instanceof DocumentInterface) {
            return ActionElasticElementType::DOCUMENT;
        }
    }
}
