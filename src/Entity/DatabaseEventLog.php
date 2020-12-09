<?php

declare(strict_types=1);

namespace NSaliu\TailDb\Entity;

final class DatabaseEventLog
{
    /**
     * @var string
     */
    private $eventName;

    /**
     * @var string|null
     */
    private $fqn;

    /**
     * @var SqlEventLog[]|null
     */
    private $queries;

    /**
     * @param SqlEventLog[]|null $queries
     */
    public function __construct(
        string $eventName,
        ?string $fqn,
        ?array $queries
    ) {
        $this->eventName = $eventName;
        $this->fqn = $fqn;
        $this->queries = $queries;
    }

    public function getEventName(): string
    {
        return $this->eventName;
    }

    public function getFqn(): ?string
    {
        return $this->fqn;
    }

    /**
     * @return SqlEventLog[]|null
     */
    public function getQueries(): ?array
    {
        return $this->queries;
    }
}
