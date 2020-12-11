<?php

declare(strict_types=1);

namespace NSaliu\TailDb\DependencyInjection;

use NSaliu\TailDb\Service\Client;
use NSaliu\TailDb\Service\Server;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class TailDbExtension extends Extension
{
    public const BUNDLE_ALIAS = 'tail_db';

    /**
     * @param array<mixed> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $loader->load('services.yaml');

        $configuration = $this->processConfiguration(
            new Configuration(),
            $configs
        );

        if (!$this->bundleIsEnabled($configuration)) {
            return;
        }

        $this->bindArgumentsToServices(
            $configuration,
            $container
        );
    }

    public function getAlias(): string
    {
        return self::BUNDLE_ALIAS;
    }

    /**
     * @param array<string, mixed> $configuration
     */
    private function bundleIsEnabled(array $configuration): bool
    {
        return $configuration['enabled'];
    }

    /**
     * @param array<string, mixed> $configuration
     */
    private function bindArgumentsToServices(
        array $configuration,
        ContainerBuilder $container
    ): void {
        $serverDefinition = $container->getDefinition(Server::class);
        $serverDefinition->setArgument('$host', $configuration['host']);
        $serverDefinition->setArgument('$port', $configuration['port']);

        $serverDefinition = $container->getDefinition(Client::class);
        $serverDefinition->setArgument('$host', $configuration['host']);
        $serverDefinition->setArgument('$port', $configuration['port']);
    }
}
