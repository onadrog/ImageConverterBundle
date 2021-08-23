<?php

namespace Onadrog\ImageConverterBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class EventTest extends WebTestCase
{
    public function testForm(): void
    {
        $file = new UploadedFile(dirname(__DIR__, 1).'/Mock/images/JPG.jpg', 'JPG');

        $client = $this->createClient();
        $client->request('POST', '/form/Media');
        $this->assertResponseIsSuccessful();
        $client->submitForm('Save', [
            'mock[name]' => 'a',
            'mock[file][image]' => $file,
        ]);
        $this->assertTrue(true);
        // $this->assertResponseRedirects('/');
    }
}
