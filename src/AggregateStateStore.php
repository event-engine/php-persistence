<?php
/**
 * This file is part of event-engine/php-persistence.
 * (c) 2018-2019 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace EventEngine\Persistence;

use EventEngine\Aggregate\Exception\AggregateNotFound;

interface AggregateStateStore
{
    /**
     * @param string $aggregateType
     * @param string $aggregateId
     * @param int|null $expectedVersion
     * @return mixed State of the aggregate
     * @throws AggregateNotFound
     */
    public function loadAggregateState(string $aggregateType, string $aggregateId, int $expectedVersion = null);

    /**
     * @param string $aggregateType
     * @param string $aggregateId
     * @param int $maxVersion
     * @return mixed State of the aggregate
     */
    public function loadAggregateStateUntil(string $aggregateType, string $aggregateId, int $maxVersion);
}
