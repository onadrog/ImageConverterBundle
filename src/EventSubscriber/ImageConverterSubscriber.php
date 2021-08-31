<?php

namespace Onadrog\ImageConverterBundle\EventSubscriber;

use Onadrog\ImageConverterBundle\Service\ImageUtils;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @author Sébastien Gordano <sebastien.gordano@gmail.com>
 */
class ImageConverterSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private array $config,
        private ImageUtils $imageUtils
    ) {
    }

    public function onFormPreSubmit(FormEvent $event): void
    {
        $data = $event->getData();
        $image = $data['image_converter'];
        if (!$image instanceof UploadedFile) {
            return;
        }
        $form = $event->getForm();
        $class = $form->getRoot()->getData();
        $property = $form->getName();

        $prop = ImageUtils::guessMappedClass($class, $property);
        $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $slug = $this->imageUtils->namer($originalName, $this->config['namer']);

        $imagesize = getimagesize($image->getPathName());
        list($imageWidth, $imageHeight) = $imagesize;

        $dimension = ['height' => $imageHeight, 'width' => $imageWidth];

        $imagePath = $image->getPathName();
        $callFunction = $this->imageUtils->createGdImg($image->guessExtension(), $imagePath);

        imagewebp($callFunction, $imagePath, $this->config['quality']);

        $data[$prop['name']] = $slug['safename'];
        $data[$prop['slug']] = $slug['slug'].'.'.$image->guessExtension();
        $data[$prop['dimension']] = json_encode($dimension);

        $event->setData($data);
        $image->move($this->config['media_uploads_path'], $slug['slug'].'.'.$image->guessExtension());
        imagedestroy($callFunction);
    }

    /**
     * Append fields form based on Attributes map.
     */
    public function onFormPreSet(FormEvent $event): void
    {
        $form = $event->getForm();
        $form = $event->getForm();
        $class = $form->getRoot()->getData();
        $attributes = ImageUtils::guessMappedClass($class, $form->getName());
        $form
            ->add($attributes['name'], HiddenType::class, [
                'mapped' => true,
                'data_class' => $attributes['entity']->name,
            ])
            ->add($attributes['slug'], HiddenType::class, [
                'mapped' => true,
                'data_class' => $attributes['entity']->name,
            ])
            ->add($attributes['dimension'], HiddenType::class, [
                'mapped' => true,
                'data_class' => $attributes['entity']->name,
                'attr' => ['data-type' => 'dimension'],
            ])
            ->add('image_converter', FileType::class, [
                'multiple' => false,
                'label' => false, 'mapped' => false,
                'attr' => ['data-entity' => $attributes['entity']->name, 'data-relation' => $attributes['relation']],
            ]);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => 'onFormPreSubmit',
            FormEvents::PRE_SET_DATA => 'onFormPreSet',
        ];
    }
}
