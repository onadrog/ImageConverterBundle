<?php

namespace Onadrog\ImageConverterBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Sébastien Gordano <sebastien.gordano@gmail.com>
 */
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
                ->enumNode('namer')->values(['default', 'uuid', 'mixed'])->defaultValue('default')->cannotBeEmpty()->end()
                ->scalarNode('public_path')->defaultValue('/uploads/media')->cannotBeEmpty()->end()
                ->booleanNode('delete_orphans')->defaultValue(true)->end()
                ->booleanNode('keep_original')->defaultValue(false)->end()
                ->booleanNode('use_js')->defaultValue(false)->end()
                ->integerNode('quality')->min(0)->max(100)->defaultValue(80)->end()
            ->end();
    }
}
