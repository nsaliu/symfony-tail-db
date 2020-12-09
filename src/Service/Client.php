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

class Client implements ClientInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var LoopInterface
     */
    private $eventLoop;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        $this->eventLoop = Factory::create();
    }

    public function push(DatabaseEventLog $databaseEventLog): void
    {
        $connector = new Connector($this->eventLoop);

        $connector->connect('127.0.0.1:31337')
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
