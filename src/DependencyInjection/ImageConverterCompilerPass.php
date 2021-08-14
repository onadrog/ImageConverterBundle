<?php

namespace Onadrog\ImageConverterBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ImageConverterCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if ($container->hasParameter('twig.form.resources')) {
            $resources = $container->getParameter('twig.form.resources');
            array_push($resources, '@ImageConverter/fields.html.twig');
            $container->setParameter('twig.form.resources', $resources);
        }
    }
}
