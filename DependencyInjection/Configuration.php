<?php

namespace VirtualAssembly\semappsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('semapps');

        $rootNode
        ->children()
            ->arrayNode('default_types')
                ->isRequired()
                ->requiresAtLeastOneElement()
                ->useAttributeAsKey('name')
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('uri')->end()
                        ->scalarNode('title')->end()
                        ->scalarNode('icon')->end()
                        ->scalarNode('tooltip')->end()
                        ->scalarNode('role')->end()
                        ->scalarNode('route')->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode('ontology_types')
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('uri')->end()
                        ->scalarNode('title')->end()
                        ->scalarNode('icon')->end()
                        ->scalarNode('tooltip')->end()
                        ->scalarNode('role')->end()
                        ->scalarNode('route')->end()
                    ->end()
                ->end()
            ->end()
        ->end();
;
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
