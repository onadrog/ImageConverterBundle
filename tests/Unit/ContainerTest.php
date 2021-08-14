<?php

namespace Onadrog\ImageConverterBundle\Tests\Unit;

use Onadrog\ImageConverterBundle\ImageConverterBundle;
use Onadrog\ImageConverterBundle\Service\ImageUtils;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerTest extends KernelTestCase
{
    private static function geKernelContainer(): ContainerInterface
    {
        self::bootKernel();

        return self::getContainer();
    }

    /**
     * Test Container DI.
     *
     * @covers \Onadrog\ImageConverterBundle\ImageConverterBundle::build
     * @covers \Onadrog\ImageConverterBundle\DependencyInjection\ImageConverterExtension::load
     * @covers \Onadrog\ImageConverterBundle\DependencyInjection\Configuration
     * @covers \Onadrog\ImageConverterBundle\DependencyInjection\ImageConverterCompilerPass::process
     */
    public function testContainerSetup(): void
    {
        $onadrog = self::geKernelContainer()->get('image_converter.utils');
        $this->assertInstanceOf(ImageUtils::class, $onadrog);
        $array = self::geKernelContainer()->getParameter('image_converter');
        $this->assertArrayHasKey('media_uploads_path', $array);
        $param = self::geKernelContainer()->hasParameter('twig.form.resources');
        $this->assertTrue($param);
        $container = self::geKernelContainer()->getParameter('twig.form.resources');
        $this->assertIsArray($container);
        $this->assertContains('@ImageConverter/fields.html.twig', $container);
    }

    /**
     * @covers \Onadrog\ImageConverterBundle\ImageConverterBundle::getPath
     */
    public function testPath()
    {
        $bundle = new ImageConverterBundle();
        $path = $bundle->getPath();
        $this->assertIsString($path);
        $this->assertEquals(dirname(__DIR__, 2), $path);
    }
}
