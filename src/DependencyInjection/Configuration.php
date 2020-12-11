<?php

declare(strict_types=1);

namespace NSaliu\TailDb\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(TailDbExtension::BUNDLE_ALIAS);

        $treeBuilder->getRootNode()
            ->children()
                ->booleanNode('enabled')->end()
                ->scalarNode('host')->end()
                ->integerNode('port')->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
