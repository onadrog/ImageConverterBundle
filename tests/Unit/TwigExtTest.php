<?php

namespace Onadrog\ImageConverterBundle\Tests\Unit;

use Onadrog\ImageConverterBundle\Twig\ImageTwigExtension;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

/**
 * @author Sébastien Gordano <sebastien.gordano@gmail.com>
 */
class TwigExtTest extends TestCase
{
    /**
     * Test methods are called.
     *
     * @cover ImageTwigExtension
     */
    public function testMethodCall(): void
    {
        $mock = $this->createMock(Environment::class);
        $twig = new ImageTwigExtension($mock);
        $this->assertSame('image_converter_img', $twig->getFunctions()[0]->getName());
    }
}
