<?php

namespace Onadrog\ImageConverterBundle\Tests\Unit;

use Onadrog\ImageConverterBundle\Twig\ImageTwigExtension;
use PHPUnit\Framework\TestCase;

/**
 * @author Sébastien Gordano <sebastien.gordano@gmail.com>
 */
class TwigExtTest extends TestCase
{
    private const PATH = '/media/pictures/';

    /**
     * @cover ImageTwigExtension::getUri
     */
    public function testUri(): void
    {
        $ext = new ImageTwigExtension(['media_uploads_path' => self::PATH]);
        $uri = $ext->getUri('hello');
        $html = $ext->addPicture('hello');
        $this->assertIsString($uri);
        $this->assertEquals(self::PATH.'hello', $uri);
        $this->assertIsString($html);
        $this->assertEquals('<picture></picture>', $html);
    }

    /**
     * Test methods are called.
     *
     * @cover ImageTwigExtension
     */
    public function testMethodCall(): void
    {
        $twig = new ImageTwigExtension(['media_upload_path' => self::PATH]);
        $this->assertSame('image_converter_picture', $twig->getFunctions()[0]->getName());
    }
}
