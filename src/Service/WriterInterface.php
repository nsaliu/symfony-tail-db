<?php

declare(strict_types=1);

namespace NSaliu\TailDb\Service;

use NSaliu\TailDb\Entity\DatabaseEventLog;

interface WriterInterface
{
    public function write(DatabaseEventLog $databaseEventLog): void;
}
