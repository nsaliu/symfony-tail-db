<?php

declare(strict_types=1);

namespace NSaliu\TailDb\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\DBAL\Logging\DebugStack;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use NSaliu\TailDb\Entity\DatabaseEventLog;
use NSaliu\TailDb\Entity\SqlEventLog;

final class DatabaseActivitySubscriber implements EventSubscriber
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::postFlush,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $args->getObjectManager();

        $entityManager->getConnection()
            ->getConfiguration()
            ->setSQLLogger(
                new DebugStack()
            );
    }

    public function postFlush(PostFlushEventArgs $args): void
    {
        /** @var DebugStack $debugStack */
        $debugStack = $args->getEntityManager()
            ->getConnection()
            ->getConfiguration()
            ->getSQLLogger();

        $this->client->push(
            $this->createDatabaseEventLog($debugStack)
        );
    }

    private function createDatabaseEventLog(DebugStack $debugStack): DatabaseEventLog
    {
        $sqlEventLogs = array_map(function (array $query) {
            return new SqlEventLog(
                $query['sql'],
                $query['params'],
                $query['types'],
                $query['executionMS']
            );
        }, $debugStack->queries);

        return new DatabaseEventLog(
            Events::postFlush,
            null,
            $sqlEventLogs
        );
    }
}
