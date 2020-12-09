<?php

declare(strict_types=1);

namespace NSaliu\TailDb\Command;

use NSaliu\TailDb\Service\WriterInterface;

interface ServerInterface
{
    public function run(WriterInterface $writer): void;
}
