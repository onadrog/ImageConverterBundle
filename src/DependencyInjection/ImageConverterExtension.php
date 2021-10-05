<?php

namespace Onadrog\ImageConverterBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * @author Sébastien Gordano <sebastien.gordano@gmail.com>
 */
class ImageConverterExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = $this->getConfiguration($configs, $container);
        $configs = $this->processConfiguration($configuration, $configs);
        $loader = new XmlFileLoader($container, new FileLocator(dirname(__DIR__, 2).'/config'));
        $files = [
            'mapping.xml', 'subscriber.xml', 'twig.xml', 'type.xml', 'utils.xml', 'command.xml', 'controller.xml',
        ];
        foreach ($files as $loads) {
            $loader->load($loads);
        }
        if (!extension_loaded('gd')) {
            $configs['use_js'] = true;
        } else {
            $configs['use_js'] = false;
        }
        $container->setParameter('image_converter', $configs);
    }
}
