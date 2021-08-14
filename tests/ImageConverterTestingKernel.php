<?php

namespace Onadrog\ImageConverterBundle\Tests;

use Onadrog\ImageConverterBundle\ImageConverterBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

class ImageConverterTestingKernel extends Kernel
{
    public function __construct()
    {
        parent::__construct(environment: $_SERVER['APP_ENV'], debug: true);
    }

    public function registerBundles()
    {
        return [
            new FrameworkBundle(), new ImageConverterBundle(),
            new TwigBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(static function (ContainerBuilder $container): void {
            $container->loadFromExtension('framework', [
                'secret' => $_SERVER['APP_SECRET'],
                'router' => [
                    'resource' => 'kernel::loadRoutes',
                    'type' => 'service',
                    'utf8' => false,
                ],
                'test' => true,
            ]);
        });
    }
}
