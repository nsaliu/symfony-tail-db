<?php

declare(strict_types=1);

namespace NSaliu\TailDb\Service;

use NSaliu\TailDb\Command\ServerInterface;
use NSaliu\TailDb\Entity\DatabaseEventLog;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\Socket\ConnectionInterface;
use React\Socket\Server as ReactPhpServer;
use Symfony\Component\Serializer\SerializerInterface;

final class Server implements ServerInterface
{
    /**
     * @var LoopInterface
     */
    private $eventLoop;

    /**
     * @var ReactPhpServer
     */
    private $server;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(
        string $host,
        int $port,
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;

        $this->eventLoop = Factory::create();

        $endpoint = sprintf(
            '%s:%d',
            $host,
            $port
        );

        $this->server = new ReactPhpServer(
            $endpoint,
            $this->eventLoop
        );
    }

    public function run(WriterInterface $writer): void
    {
        $this->server->on('connection', function (ConnectionInterface $connection) use ($writer) {
            $connection->on('data', function ($data) use ($connection, $writer) {
                /** @var DatabaseEventLog $databaseEventLog */
                $databaseEventLog = $this->serializer->deserialize(
                    $data,
                    DatabaseEventLog::class,
                    'json'
                );

                $writer->write($databaseEventLog);

                $connection->close();
            });
        });

        $this->eventLoop->run();
    }
}
