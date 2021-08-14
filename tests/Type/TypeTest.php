<?php

namespace Onadrog\ImageConverterBundle\Tests\Type;

use Onadrog\ImageConverterBundle\Entity\Media;
use Onadrog\ImageConverterBundle\EventSubscriber\ImageConverterSubscriber;
use Onadrog\ImageConverterBundle\Service\ImageUtils;
use Onadrog\ImageConverterBundle\Type\ImageConverterType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TypeTest extends TypeTestCase
{
    private $subscriber;
    private $utils;

    protected function setUp(): void
    {
        /* @var ImageUtils|\PHPUnit\Framework\MockObject\MockObject */
        $this->utils = $this->createMock(ImageUtils::class);
        $this->utils->method('createGdImg')->willReturn(\imagecreatefromjpeg(dirname(__DIR__, 1).'/Mock/images/STUB.jpeg'));
        $this->utils->expects($this->once())->method('createGdImg');

        $this->subscriber = new ImageConverterSubscriber(
            ['namer' => 'default', 'quality' => 80],
            $this->utils
        );

        parent::setUp();
    }

    protected function getExtensions()
    {
        $type = new ImageConverterType(
            $this->subscriber
        );

        return [
            new PreloadedExtension([$type], []),
        ];
    }

    /**
     * Test ImaconvertType work wirh evntsubscriber and return imageWebp():bool.
     */
    public function testTypeTrigger(): void
    {
        $formData = [
            'image' => new UploadedFile(dirname(__DIR__, 1).'/Mock/images/JPG.jpg', 'JPG'),
        ];

        $form = $this->factory->createNamed('file', ImageConverterType::class, new Media());
        $form->submit($formData);
        $this->isTrue();
    }
}
