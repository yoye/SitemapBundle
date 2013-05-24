<?php

namespace OpenSky\Bundle\SitemapBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('opensky_sitemap');
        
        $supportedDrivers = array('orm', 'odm');

        $rootNode
                ->children()
                    ->scalarNode('db_driver')
                        ->validate()
                            ->ifNotInArray($supportedDrivers)
                            ->thenInvalid('The driver %s is not supported. Please choose one of '.json_encode($supportedDrivers))
                        ->end()
                        ->cannotBeOverwritten()
                        ->defaultValue('odm')
                    ->end()
                    ->scalarNode('default_lastmod')
                        ->defaultNull()
                    ->end()
                    ->scalarNode('default_changefreq')
                        ->defaultNull()
                    ->end()
                    ->scalarNode('default_priority')
                        ->defaultNull()
                    ->end()
                    ->scalarNode('url_class')
                        ->defaultValue('Doctrine\ODM\MongoDB\DocumentRepository')
                    ->end()
                ->end()
        ;

        return $treeBuilder;
    }

}
