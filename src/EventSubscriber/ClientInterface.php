<?php

declare(strict_types=1);

namespace NSaliu\TailDb\EventSubscriber;

use NSaliu\TailDb\Entity\DatabaseEventLog;

interface ClientInterface
{
    public function push(DatabaseEventLog $databaseEventLog): void;
}
