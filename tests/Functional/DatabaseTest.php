<?php

namespace Onadrog\ImageConverterBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @author Sébastien Gordano <sebastien.gordano@gmail.com>
 */
class DatabaseTest extends WebTestCase
{
    private const UPLOAD_PATH = 'public/uploads/media/';

    private function getClientForm(string $fileName, string $className, string $property)
    {
        $path = dirname(__DIR__, 1).'/Mock/images/'.strtoupper($fileName).'.'.$fileName;

        $file = new UploadedFile($path, strtoupper($fileName));
        if ('Product' === $className) {
            $data = [
                "mock[$property][image_converter]" => $file,
                'mock[name]' => 'yo',
            ];
        } else {
            $data = [
                "mock[$property][image_converter]" => $file,
                "mock[$property][alt]" => 'alt img.',
            ];
        }
        $client = $this->createClient();
        $client->request('POST', "/form/$className/$property");
        $client->submitForm('Save', $data);

        return $client;
    }

    /**
     * test Entity without relational Mapping
     * SoloFile.
     */
    public function testSave()
    {
        $this->getClientForm('jpg', 'SoloFile', 'file');
        $this->assertFileExists(self::UPLOAD_PATH.'JPG.webp');
        $this->assertResponseRedirects('/');
    }

    /**
     * test entity with relational mapping
     * Product.
     */
    public function testRelational()
    {
        $this->getClientForm('jpg', 'Product', 'media');
        $this->assertFileExists(self::UPLOAD_PATH.'JPG.webp');
        $this->assertResponseRedirects('/');
    }

    /*
     * test form on edit mapped attributes must be in inputs.
     */
    public function testFormDataMapperValue()
    {
        $client = $this->createClient();
        $client->request('GET', '/form/solofile/1/edit');
        $this->assertInputValueSame('solo[file][alt]', 'A fixture image.');
    }
}
