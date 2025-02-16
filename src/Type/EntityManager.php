<?php

declare(strict_types=1);

namespace Syndesi\ElasticEntityManager\Type;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Response\Elasticsearch;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Syndesi\ElasticDataStructures\Contract\DocumentInterface;
use Syndesi\ElasticDataStructures\Type\Document;
use Syndesi\ElasticEntityManager\Contract\EntityManagerInterface;
use Syndesi\ElasticEntityManager\Event\PostFlushEvent;
use Syndesi\ElasticEntityManager\Event\PreFlushEvent;
use Syndesi\ElasticEntityManager\Exception\ElasticEntityManagerException;
use Syndesi\ElasticEntityManager\Helper\LifecycleEventHelper;

class EntityManager implements EntityManagerInterface
{
    private Client $client;
    private ?LoggerInterface $logger;
    /**
     * @var ActionElasticElement[]
     */
    private array $queue = [];
    private EventDispatcherInterface $dispatcher;

    public function __construct(Client $client, EventDispatcherInterface $dispatcher, ?LoggerInterface $logger = null)
    {
        $this->client = $client;
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
    }

    public function add(ActionType $actionType, DocumentInterface $element): self
    {
        $actionElasticElement = new ActionElasticElement($actionType, $element);
        $this->queue[] = $actionElasticElement;

        return $this;
    }

    public function create(DocumentInterface $element): self
    {
        $this->add(ActionType::CREATE, $element);

        return $this;
    }

    public function merge(DocumentInterface $element): self
    {
        $this->add(ActionType::MERGE, $element);

        return $this;
    }

    public function delete(DocumentInterface $element): self
    {
        $this->add(ActionType::DELETE, $element);

        return $this;
    }

    public function flush(): self
    {
        $this->logger?->debug("Dispatching PreFlushEvent");
        $this->dispatcher->dispatch(new PreFlushEvent());
        foreach ($this->queue as $actionElasticElement) {
            $events = LifecycleEventHelper::getLifecycleEventForElasticActionElement($actionElasticElement, true);
            foreach ($events as $event) {
                $this->logger?->debug(sprintf("Dispatching %s", (new \ReflectionClass($event))->getShortName()));
                $this->dispatcher->dispatch($event);
            }

            $element = $actionElasticElement->getElement();

            if (ActionType::CREATE === $actionElasticElement->getAction()) {
                $properties = $element->getProperties();
                unset($properties['_id']);
                $this->logger?->debug(sprintf(
                    "Creating element of type %s",
                    get_class($element)
                ), [
                    'index' => $element->getIndex(),
                    'identifier' => $element->getIdentifier(),
                    'properties' => $properties,
                ]);
                /**
                 * @psalm-suppress ArgumentTypeCoercion
                 */
                $this->client->index([
                    'index' => $element->getIndex(),
                    'id' => (string) $element->getIdentifier(),
                    'body' => $properties,
                ]);
            }
            if (ActionType::MERGE === $actionElasticElement->getAction()) {
                $properties = $element->getProperties();
                unset($properties['_id']);
                $this->logger?->debug(sprintf(
                    "Updating element of type %s",
                    get_class($element)
                ), [
                    'index' => $element->getIndex(),
                    'identifier' => $element->getIdentifier(),
                    'properties' => $properties,
                ]);
                /**
                 * @psalm-suppress ArgumentTypeCoercion
                 */
                $this->client->update([
                    'index' => $element->getIndex(),
                    'id' => (string) $element->getIdentifier(),
                    'body' => [
                        'doc' => $properties,
                        'doc_as_upsert' => true,
                    ],
                ]);
            }
            if (ActionType::DELETE === $actionElasticElement->getAction()) {
                $this->logger?->debug(sprintf(
                    "Deleting element of type %s",
                    get_class($element)
                ), [
                    'index' => $element->getIndex(),
                    'identifier' => $element->getIdentifier(),
                ]);
                /**
                 * @psalm-suppress ArgumentTypeCoercion
                 */
                $this->client->delete([
                    'index' => $element->getIndex(),
                    'id' => (string) $element->getIdentifier(),
                ]);
            }

            $events = LifecycleEventHelper::getLifecycleEventForElasticActionElement($actionElasticElement, false);
            foreach ($events as $event) {
                $this->logger?->debug(sprintf("Dispatching %s", (new \ReflectionClass($event))->getShortName()), [
                    'element' => $event->getElement(),
                ]);
                $this->dispatcher->dispatch($event);
            }
        }

        $this->clear();

        $this->logger?->debug("Dispatching PostFlushEvent");
        $this->dispatcher->dispatch(new PostFlushEvent());

        return $this;
    }

    public function getOneByIdentifier(string $index, string $identifier): ?DocumentInterface
    {
        try {
            /**
             * @psalm-suppress InvalidArgument
             */
            $res = $this->client->get([
                'index' => $index,
                'id' => $identifier,
            ]);
            if (!($res instanceof Elasticsearch)) {
                throw new ElasticEntityManagerException(sprintf("Elastic returned response of the type '%s', expected type 'Elasticsearch'.", get_class($res)));
            }
            $res = $res->asArray();
        } catch (ClientResponseException $e) {
            return null;
        }

        if (0 === $res['found']) {
            return null;
        }

        return (new Document())
            ->setIndex($res['_index'])
            ->setIdentifier($res['_id'])
            ->addProperties($res['_source']);
    }

    public function clear(): self
    {
        $this->queue = [];

        return $this;
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
