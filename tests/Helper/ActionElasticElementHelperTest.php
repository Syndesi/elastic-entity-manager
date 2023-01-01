<?php

declare(strict_types=1);

namespace Syndesi\ElasticEntityManager\Tests\Helper;

use PHPUnit\Framework\TestCase;
use Syndesi\ElasticDataStructures\Type\Document;
use Syndesi\ElasticEntityManager\Helper\ActionElasticElementHelper;
use Syndesi\ElasticEntityManager\Type\ActionElasticElement;
use Syndesi\ElasticEntityManager\Type\ActionElasticElementType;
use Syndesi\ElasticEntityManager\Type\ActionType;

class ActionElasticElementHelperTest extends TestCase
{
    public function provideActionElasticElementWithType()
    {
        return [
            [
                new ActionElasticElement(ActionType::CREATE, new Document()),
                ActionElasticElementType::DOCUMENT,
            ],
        ];
    }

    /**
     * @dataProvider provideActionElasticElementWithType
     */
    public function testGetTypeFromActionElasticElement(ActionElasticElement $object, ActionElasticElementType $expectedType): void
    {
        $foundType = ActionElasticElementHelper::getTypeFromActionElasticElement($object);
        $this->assertSame($expectedType, $foundType);
    }
}
