<?php

declare(strict_types=1);

namespace Syndesi\ElasticEntityManager\Type;

enum ActionType: string
{
    case CREATE = 'CREATE';
    case MERGE = 'MERGE';
    case DELETE = 'DELETE';
}
