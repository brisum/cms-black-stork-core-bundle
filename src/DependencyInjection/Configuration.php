<?php

namespace Brisum\Stork\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('stork_core');

        $rootNode
            ->children()
                ->arrayNode('seo')
                    ->children()
                        ->arrayNode('templates')
                            ->isRequired()
                            ->requiresAtLeastOneElement()
                            ->useAttributeAsKey('name')
                            ->arrayPrototype()
                                ->prototype('scalar')
                                ->end()
                            ->end()
                        ->end() // templates
                    ->end()
                ->end()
                ->arrayNode('page')
                    ->children()
                        ->arrayNode('templates')
                            ->isRequired()
                            ->requiresAtLeastOneElement()
                            ->useAttributeAsKey('name')
                            ->prototype('scalar')
                            ->end()
                        ->end() // templates
                        ->arrayNode('statuses')
                            ->isRequired()
                            ->requiresAtLeastOneElement()
                            ->useAttributeAsKey('name')
                            ->prototype('scalar')
                            ->end()
                        ->end() // statuses
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}