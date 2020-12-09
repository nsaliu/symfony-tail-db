<?php

declare(strict_types=1);

namespace NSaliu\TailDb\Command;

use NSaliu\TailDb\Service\ConsoleWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class TailDbCommand extends Command
{
    protected static $defaultName = 'doctrine:tail';

    /**
     * @var ServerInterface
     */
    private $server;

    public function __construct(ServerInterface $server)
    {
        $this->server = $server;

        parent::__construct();
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $this->server->run(
            new ConsoleWriter($output)
        );

        return 1;
    }
}
