<?php

namespace Onadrog\ImageConverterBundle\Tests;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use Onadrog\ImageConverterBundle\ImageConverterBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\MakerBundle\MakerBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class ImageConverterTestingKernel extends Kernel
{
    use MicroKernelTrait;

    public function __construct()
    {
        parent::__construct(environment: $_SERVER['APP_ENV'], debug: true);
    }

    public function registerBundles()
    {
        return [
            new FrameworkBundle(), new ImageConverterBundle(),
            new TwigBundle(), new DoctrineBundle(), new DoctrineMigrationsBundle(), new DoctrineFixturesBundle(), new MakerBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/Mock/config/config.yaml');
    }

    public function getProjectDir()
    {
        return dirname(__DIR__, 1);
    }
}
