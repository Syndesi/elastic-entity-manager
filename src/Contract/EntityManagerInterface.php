<?php

declare(strict_types=1);

namespace Syndesi\ElasticEntityManager\Contract;

use Elastic\Elasticsearch\Client;
use Syndesi\ElasticDataStructures\Contract\DocumentInterface;
use Syndesi\ElasticEntityManager\Type\ActionType;

interface EntityManagerInterface
{
    public function add(ActionType $actionType, DocumentInterface $element): self;

    public function create(DocumentInterface $element): self;

    public function merge(DocumentInterface $element): self;

    public function delete(DocumentInterface $element): self;

    public function flush(): self;

    public function clear(): self;

    public function getClient(): Client;
}
