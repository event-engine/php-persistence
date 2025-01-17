<?php
/**
 * This file is part of event-engine/php-persistence.
 * (c) 2018-2021 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace EventEngine\Persistence;

use EventEngine\EventStore\EventStore;
use EventEngine\Messaging\GenericEvent;

trait InnerEventStore
{
    /**
     * @var EventStore
     */
    private $eventStore;

    public function createStream(string $streamName): void
    {
        $this->eventStore->createStream($streamName);
    }

    public function deleteStream(string $streamName): void
    {
        $this->eventStore->deleteStream($streamName);
    }

    public function appendTo(string $streamName, GenericEvent ...$events): void
    {
        $this->eventStore->appendTo($streamName, ...$events);
    }

    /**
     * @param string $streamName
     * @param string $aggregateType
     * @param string $aggregateId
     * @param int $minVersion
     * @param int|null $maxVersion
     * @return \Iterator GenericEvent[]
     */
    public function loadAggregateEvents(
        string $streamName,
        string $aggregateType,
        string $aggregateId,
        int $minVersion = 1,
        ?int $maxVersion = null
    ): \Iterator {
        return $this->eventStore->loadAggregateEvents(
            $streamName,
            $aggregateType,
            $aggregateId,
            $minVersion,
            $maxVersion
        );
    }

    /**
     * @param string $streamName
     * @param string $correlationId
     * @return \Iterator GenericEvent[]
     */
    public function loadEventsByCorrelationId(string $streamName, string $correlationId): \Iterator
    {
        return $this->eventStore->loadEventsByCorrelationId($streamName, $correlationId);
    }

    /**
     * @param string $streamName
     * @param string $causationId
     * @return \Iterator GenericEvent[]
     */
    public function loadEventsByCausationId(string $streamName, string $causationId): \Iterator
    {
        return $this->eventStore->loadEventsByCausationId($streamName, $causationId);
    }
}
