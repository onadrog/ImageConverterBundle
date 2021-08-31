<?php

namespace Onadrog\ImageConverterBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @author Sébastien Gordano <sebastien.gordano@gmail.com>
 */
class ImageTwigExtensionTest extends WebTestCase
{
    public function testExtension(): void
    {
        $client = $this->createClient();
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('picture', '');
    }
}
