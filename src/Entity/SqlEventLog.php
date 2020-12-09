<?php

declare(strict_types=1);

namespace NSaliu\TailDb\Entity;

final class SqlEventLog
{
    /**
     * @var string
     */
    private $sql;

    /**
     * @var array<int, mixed>|null
     */
    private $params;

    /**
     * @var array<int, string>|null
     */
    private $types;

    /**
     * @var float
     */
    private $executionMS;

    /**
     * @param array<int, mixed>|null  $params
     * @param array<int, string>|null $types
     */
    public function __construct(
        string $sql,
        ?array $params,
        ?array $types,
        float $executionMS
    ) {
        $this->sql = $sql;
        $this->params = $params;
        $this->types = $types;
        $this->executionMS = $executionMS;
    }

    public function getSql(): string
    {
        return $this->sql;
    }

    /**
     * @return array<int, mixed>|null
     */
    public function getParams(): ?array
    {
        return $this->params;
    }

    /**
     * @return array<int, string>|null
     */
    public function getTypes(): ?array
    {
        return $this->types;
    }

    public function getExecutionMS(): float
    {
        return $this->executionMS;
    }
}
