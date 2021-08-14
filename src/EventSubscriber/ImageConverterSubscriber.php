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

    public function onFormPreSubmit(FormEvent $event): bool
    {
        $upladedInstance = $event->getData();
        $image = $upladedInstance['image'];
        if (!$image instanceof UploadedFile) {
            return false;
        }
        $form = $event->getForm();
        $class = $form->getRoot()->getData();

        ImageUtils::guessMappedClass($class, $form->getName());
        $form = $event->getForm();
        $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $this->imageUtils->namer($originalName, $this->config['namer']);

        $imagePath = $image->getPathName();
        $callFunction = $this->imageUtils->createGdImg($image->guessExtension(), $imagePath);

        return imagewebp($callFunction, $imagePath, $this->config['quality']);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'form.pre_submit' => 'onFormPreSubmit',
        ];
    }
}
