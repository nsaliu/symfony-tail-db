<?php

declare(strict_types=1);

namespace NSaliu\TailDb\Service;

use NSaliu\TailDb\Entity\DatabaseEventLog;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleWriter implements WriterInterface
{
    /**
     * @var OutputInterface
     */
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function write(DatabaseEventLog $databaseEventLog): void
    {
        $this->writePostFlush($databaseEventLog);
    }

    private function writePostFlush(DatabaseEventLog $databaseEventLog): void
    {
        if ($databaseEventLog->getQueries() === null) {
            return;
        }

        $rows = [];

        foreach ($databaseEventLog->getQueries() as $sqlEventLog) {
            $row = [
                $databaseEventLog->getEventName(),
                round($sqlEventLog->getExecutionMS(), 4),
                $sqlEventLog->getSql(),
                $sqlEventLog->getParams() !== null ? join("\n", array_values($sqlEventLog->getParams())) : '--',
                $sqlEventLog->getTypes() !== null ? join("\n", array_values($sqlEventLog->getTypes())) : '--',
            ];

            $rows[] = $row;
            $rows[] = new TableSeparator();
        }

        array_pop($rows);

        $table = new Table($this->output);
        $table->setStyle('box');
        $table
            ->setHeaders(['Event name', 'Execution time', 'SQL statement', 'Params', 'Types'])
            ->setRows($rows);

        $table->render();
    }
}
