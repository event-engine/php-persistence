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

use EventEngine\Persistence\Exception\TransactionAlreadyStarted;
use EventEngine\Persistence\Exception\TransactionNotStarted;
use ReturnTypeWillChange;

final class InMemoryConnection implements \ArrayAccess, TransactionalConnection
{
    /**
     * We need these defaults, otherwise array access won't work
     *
     * @var array
     */
    private $storage = [
        'events' => [],
        'event_streams' => [],
        'projections' => [],
        'documents' => [],
        'documentIndices' => [],
    ];

    /**
     * @var array
     */
    private $snapshot = [];

    /**
     * @var bool
     */
    private $inTransaction = false;

    public function beginTransaction(): void
    {
        if ($this->inTransaction) {
            throw new TransactionAlreadyStarted();
        }
        $this->snapshot = $this->storage;
        $this->inTransaction = true;
    }

    public function commit(): void
    {
        if (! $this->inTransaction) {
            throw TransactionNotStarted::commit();
        }

        $this->snapshot = [];
        $this->inTransaction = false;
    }

    public function rollBack(): void
    {
        if (! $this->inTransaction) {
            throw TransactionNotStarted::rollback();
        }

        $this->storage = $this->snapshot;

        $this->snapshot = [];
        $this->inTransaction = false;
    }

    #[ReturnTypeWillChange] public function offsetSet($key, $value): void
    {
        if (null === $key) {
            $this->storage[] = $value;
        } else {
            $this->storage[$key] = $value;
        }
    }

    #[\ReturnTypeWillChange] public function offsetExists($key): bool
    {
        return isset($this->storage[$key]);
    }

    #[ReturnTypeWillChange] public function offsetUnset($key): void
    {
        if ($this->inTransaction) {
            throw new \RuntimeException('In transaction use roll back');
        }
        unset($this->storage[$key]);
    }

    #[ReturnTypeWillChange] public function &offsetGet($key)
    {
        $ret = null;
        if (! $this->offsetExists($key)) {
            return $ret;
        }
        $ret = &$this->storage[$key];

        return $ret;
    }

    public function inTransaction(): bool
    {
        return $this->inTransaction;
    }
}
