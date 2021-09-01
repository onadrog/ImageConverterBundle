<?php

namespace Onadrog\ImageConverterBundle\EventSubscriber;

use Onadrog\ImageConverterBundle\Service\ImageUtils;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
        private ImageUtils $imageUtils,
        private FilesystemAdapter $cache,
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

        $cachedValues = $this->cache->getItem($property);
        $prop = $cachedValues->get();

        $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

        $namerOptions = ['namer' => $this->config['namer'], 'public_path' => $this->config['public_path']];
        $slug = $this->imageUtils->namer($originalName, $namerOptions);

        $imagesize = getimagesize($image->getPathName());
        list($imageWidth, $imageHeight) = $imagesize;

        $dimension = ['height' => $imageHeight, 'width' => $imageWidth];

        $imagePath = $image->getPathName();
        $callFunction = $this->imageUtils->createGdImg($image->guessExtension(), $imagePath);

        imagewebp($callFunction, $imagePath, $this->config['quality']);

        $data[$prop['name']] = $slug['safename'].'.'.$image->guessExtension();
        $data[$prop['slug']] = $slug['slug'].'.'.$image->guessExtension();
        $data[$prop['dimension']] = json_encode($dimension);
        if ('' === $data[$prop['alt']]) {
            $data[$prop['alt']] = $slug['safename'];
        }

        $event->setData($data);
        imagedestroy($callFunction);
    }

    /**
     * Append fields form based on Attributes map.
     */
    public function onFormPreSet(FormEvent $event): void
    {
        $form = $event->getForm();
        $class = $form->getRoot()->getData();
        $prop = $form->getName();
        $attributes = ImageUtils::guessMappedClass($class, $form->getName());

        $cachedAttr = $this->cache->getItem($prop);
        $cachedAttr->set($attributes);
        $this->cache->save($cachedAttr);
        $form
            ->add($attributes['name'], HiddenType::class, [
                'mapped' => true,
                'data_class' => null,
            ])
            ->add($attributes['slug'], HiddenType::class, [
                'mapped' => true,
                'data_class' => null,
            ])
            ->add($attributes['dimension'], HiddenType::class, [
                'mapped' => true,
                'data_class' => null,
                'attr' => ['data-type' => 'dimension'],
            ])
            ->add($attributes['alt'], TextType::class, [
                'mapped' => true,
                'required' => false,
                'data_class' => null,
            ])
            ->add('image_converter', FileType::class, [
                'multiple' => false,
                'label' => false, 'mapped' => false,
                'attr' => ['data-entity' => $attributes['entity'], 'data-relation' => $attributes['relation']],
            ]);
    }

    public function onFormPostSubmit(FormEvent $event): void
    {
        $form = $event->getForm();
        $this->cache->deleteItem($form->getName());
        $key = $form->get('slug')->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image_converter')->getData();
            $image->move($this->config['media_uploads_path'], $key);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => 'onFormPreSubmit',
            FormEvents::PRE_SET_DATA => 'onFormPreSet',
            FormEvents::POST_SUBMIT => 'onFormPostSubmit',
        ];
    }
}
