<?php

namespace Onadrog\ImageConverterBundle\EventSubscriber;

use Onadrog\ImageConverterBundle\Service\ImageUtils;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageConverterSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private array $config,
        private ImageUtils $imageUtils
    ) {
    }

    public function onFormPreSubmit(FormEvent $event): void
    {
        $upladedInstance = $event->getData();
        $formParent = $event->getForm()->getParent();
        $image = $upladedInstance['image'];
        if (!$image instanceof UploadedFile) {
            return;
        }
        $form = $event->getForm();
        $class = $form->getRoot()->getData();
        $property = $form->getName();

        $data = ImageUtils::guessMappedClass($class, $property);
        $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $slug = $this->imageUtils->namer($originalName, $this->config['namer']);

        $imagesize = getimagesize($image->getPathName());
        list($imageWidth, $imageHeight) = $imagesize;

        $dimension = ['height' => $imageHeight, 'width' => $imageWidth];

        $imagePath = $image->getPathName();
        $callFunction = $this->imageUtils->createGdImg($image->guessExtension(), $imagePath);

        imagewebp($callFunction, $imagePath, $this->config['quality']);
        $value = [
            $data['name'] => $slug['safeName'],
            $data['slug'] => $slug['slug'],
            $data['dimension'] => $dimension,
            $property => $data['entity']->newInstance()
        ];

        foreach ($value as $k => $v) {
            $key = is_array($k) ? key($k) : $k;
            $formParent->add($key);
            dd($formParent[$key]);
            if ($key === $formParent[$key]) {
                $formParent[$key] = $v;
            }
            //dump($formParent[$key]);
            // $upladedInstance[$key] = $v;
        }

        $event->setData($formParent);

        $image->move($this->config['media_uploads_path'], $slug['slug'] . '.' . $image->guessExtension());
        imagedestroy($callFunction);
    }


    public function onFormSubmit(FormEvent $event)
    {
        dd($event->getForm()->getData());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'form.pre_submit' => 'onFormPreSubmit',
            'form.submit' => 'onFormSubmit'
        ];
    }
}
