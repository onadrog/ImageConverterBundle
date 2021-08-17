<?php

namespace Onadrog\ImageConverterBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('image_converter');
        $rootNode = $treeBuilder->getRootNode();
        $this->addSubscriberConfiguration($rootNode);

        return $treeBuilder;
    }

    public function addSubscriberConfiguration(NodeDefinition $node): void
    {
        $node
            ->children()
                ->scalarNode('media_uploads_path')->defaultValue('%kernel.project_dir%/public/uploads/media')->cannotBeEmpty()->end()
                ->enumNode('namer')->values(['default', 'uuid', 'mixed'])->defaultValue('defaut')->cannotBeEmpty()->end()
                ->integerNode('quality')->min(0)->max(100)->defaultValue(80)->end()
            ->end();
    }
}
