<?php

declare(strict_types=1);

namespace Syndesi\ElasticEntityManager\Tests;

use Dotenv\Dotenv;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Selective\Container\Container;
use Syndesi\ElasticEntityManager\Type\EntityManager;

class FeatureTestCase extends ContainerTestCase
{
    protected Container $container;

    protected function setUp(): void
    {
        parent::setUp();

        $dotenv = Dotenv::createImmutable(__DIR__."/../");
        $dotenv->safeLoad();

        if (!array_key_exists('ENABLE_FEATURE_TEST', $_ENV)) {
            $this->markTestSkipped();
        }
        if (array_key_exists('LEAK', $_ENV)) {
            $this->markTestSkipped();
        }

        $client = ClientBuilder::create()
            ->setHosts([$_ENV['ELASTIC_AUTH']])
            ->build();

        try {
            $client->indices()->delete([
                'index' => 'test',
            ]);
        } catch (ClientResponseException $e) {
            // ignore exception if index does not exist
        }

        $this->container->set(Client::class, $client);
        $entityManager = new EntityManager(
            $client,
            $this->container->get(EventDispatcherInterface::class),
            $this->container->get(LoggerInterface::class)
        );
        $this->container->set(EntityManager::class, $entityManager);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function assertCollectionDocumentCount(string $index, int $expectedCount): void
    {
        $em = $this->container->get(EntityManager::class);
        $client = $em->getClient();
        $count = 0;
        try {
            $res = $client->count([
                'index' => $index,
            ]);
            $count = $res->asArray()['count'];
        } catch (ClientResponseException $e) {
        }
        $this->assertSame($expectedCount, $count, "Collection document count does not match.");
    }
}
