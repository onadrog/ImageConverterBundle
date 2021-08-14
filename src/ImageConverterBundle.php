<?php

namespace Onadrog\ImageConverterBundle;

use Onadrog\ImageConverterBundle\DependencyInjection\ImageConverterCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Sébastien Gordano <sebastien.gordano@gmail.com>
 */
class ImageConverterBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new ImageConverterCompilerPass());
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
