<?php

namespace Onadrog\ImageConverterBundle\EventSubscriber;

use Onadrog\ImageConverterBundle\Service\ImageUtils;
use Symfony\Component\Cache\Adapter\AdapterInterface;
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
    private const NAME_VAL = 'cache_image_converter_name_input';

    public function __construct(
        private array $config,
        private ImageUtils $imageUtils,
        private AdapterInterface $cache,
    ) {
    }

    /**
     * Get UploadedFile instance, convert to Webp format,
     * Set inputs values based on ImageUploadProperties attribute.
     */
    public function onFormPreSubmit(FormEvent $event): void
    {
        $data = $event->getData();
        $image = $data['image_converter'];
        if (!$image instanceof UploadedFile) {
            return;
        }
        $prop = $this->cache->getItem(ImageUtils::CACHE_KEY)->get();

        $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

        //Sanitize the image name to be slugy friendly
        $namerOptions = ['namer' => $this->config['namer'], 'public_path' => $this->config['public_path']];
        $slug = $this->imageUtils->namer($originalName, $namerOptions);

        $imagesize = getimagesize($image->getPathName());
        list($imageWidth, $imageHeight) = $imagesize;

        $dimension = ['height' => $imageHeight, 'width' => $imageWidth];

        $imagePath = $image->getPathName();
        // ToDo: V2 will use Js to convert, for now just return the original file
        if (!empty(get_extension_funcs('gd'))) {
            $callFunction = $this->imageUtils->createGdImg($image->guessExtension(), $imagePath);
            imagewebp($callFunction, $imagePath, $this->config['quality']);
            imagedestroy($callFunction);
        }

        // Set data to the given mapped inputs
        $data[$prop['name']] = $slug['safename'].'.'.$image->guessExtension();
        $data[$prop['slug']] = $slug['slug'].'.'.$image->guessExtension();
        $data[$prop['dimension']] = json_encode($dimension);
        if ('' === $data[$prop['alt']]) {
            $data[$prop['alt']] = $slug['safename'];
        }

        $event->setData($data);

        // remove older file for the edit action
        if ($this->config['delete_orphans']) {
            $form = $event->getForm();
            $cache_name = $this->cache->getItem(self::NAME_VAL);
            $cache_name_val = $cache_name->get();
            if ($data[$prop['name']] !== $cache_name_val &&
            null !== $cache_name_val &&
             file_exists($this->config['media_uploads_path'].$form[$prop['name']]->getData())
             ) {
                unlink($this->config['media_uploads_path'].$form[$prop['name']]->getData());
            }
        }
    }

    /**
     * Append fields form based on Attributes map.
     */
    public function onFormPreSet(FormEvent $event): void
    {
        $form = $event->getForm();
        $class = $form->getRoot()->getData();
        $attributes = ImageUtils::guessMappedClass($class, $form->getName());

        $cachedAttr = $this->cache->getItem(ImageUtils::CACHE_KEY);
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
                'label' => false,
                'mapped' => false,
                'required' => false,
                'attr' => ['data-entity' => $attributes['entity'], 'data-relation' => $attributes['relation']],
            ]);
    }

    /**
     * Verify form,
     * Delete cache items,
     * Move image to the given config path.
     */
    public function onFormPostSubmit(FormEvent $event): void
    {
        $form = $event->getForm();
        $this->cache->deleteItem(ImageUtils::CACHE_KEY);
        $this->cache->deleteItem(self::NAME_VAL);
        $key = $form->get('slug')->getData();
        $image = $form->get('image_converter')->getData();
        if ($form->isSubmitted() && $form->isValid() && $image) {
            $path = $this->imageUtils->strAppendSlash($this->config['media_uploads_path']);
            $image->move($path, $key);
        }
    }

    /**
     * Create a cache item of the 'name' form input value
     * used to verify changes on edit action.
     */
    public function onFormPostSet(FormEvent $event): void
    {
        $form = $event->getForm();
        $attributes = $this->cache->getItem(ImageUtils::CACHE_KEY)->get();
        $cache_name = $this->cache->getItem(self::NAME_VAL);
        $cache_name->set($form[$attributes['name']]->getData());
        $this->cache->save($cache_name);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => 'onFormPreSubmit',
            FormEvents::PRE_SET_DATA => 'onFormPreSet',
            FormEvents::POST_SUBMIT => 'onFormPostSubmit',
            FormEvents::POST_SET_DATA => 'onFormPostSet',
        ];
    }
}
