<?php

namespace Onadrog\ImageConverterBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DatabaseTest extends WebTestCase
{
    private const UPLOAD_PATH = 'public/uploads/media/';

    public function testSave()
    {
        $path = dirname(__DIR__, 1).'/Mock/images/JPG.jpg';

        $file = new UploadedFile($path, 'JPG');

        $client = $this->createClient();
        $client->request('POST', '/form/Media');
        $client->submitForm('Save', [
            'mock[name]' => 'd',
            'mock[file][image]' => $file,
        ]);
        $this->assertFileExists(self::UPLOAD_PATH.'JPG.webp');
        // $this->assertResponseRedirects('/');
    }
}
