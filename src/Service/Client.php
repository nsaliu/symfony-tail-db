<?php

declare(strict_types=1);

namespace NSaliu\TailDb\Service;

use NSaliu\TailDb\Entity\DatabaseEventLog;
use NSaliu\TailDb\EventSubscriber\ClientInterface;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\Socket\ConnectionInterface;
use React\Socket\Connector;
use Symfony\Component\Serializer\SerializerInterface;

final class Client implements ClientInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var LoopInterface
     */
    private $eventLoop;

    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    public function __construct(
        string $host,
        int $port,
        SerializerInterface $serializer
    ) {
        $this->host = $host;
        $this->port = $port;
        $this->serializer = $serializer;

        $this->eventLoop = Factory::create();
    }

    public function push(DatabaseEventLog $databaseEventLog): void
    {
        $connector = new Connector($this->eventLoop);

        $endpoint = sprintf(
            '%s:%d',
            $this->host,
            $this->port
        );

        $connector->connect($endpoint)
            ->then(function (ConnectionInterface $connection) use ($databaseEventLog) {
                $connection->write(
                    $this->serializer->serialize(
                        $databaseEventLog,
                        'json'
                    )
                );
            });

        $this->eventLoop->run();
    }
}
