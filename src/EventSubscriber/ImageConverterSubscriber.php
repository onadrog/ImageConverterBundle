<?php

namespace Onadrog\ImageConverterBundle\EventSubscriber;

use Onadrog\ImageConverterBundle\Form\Type\ImageChoiceType;
use Onadrog\ImageConverterBundle\Service\ImageUtils;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\File as FileConstraints;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
        private ValidatorInterface $validatorInterface,
    ) {
    }

    /**
     * Get UploadedFile instance, convert to Webp format,
     * Set inputs values based on ImageUploadProperties attribute.
     */
    public function onFormPreSubmit(FormEvent $event): void
    {
        $isWebp = false;
        $data = $event->getData();
        $image = $data['image_converter'];
        if (!$image instanceof UploadedFile) {
            return;
        }
        $mimeTypes = [];

        if ('webp' === $image->guessExtension()) {
            $isWebp = true;
            $mimeTypes = [$image->guessExtension()];
        }
        // Constraints
        if ($this->validatorInterface->validate($image,
        new FileConstraints(['mimeTypes' => ['image/*']]))->count() > 0) {
            $error_msg = new FormError('Invalid type file please upload a valid image.');
            $event->getForm()->addError($error_msg);
        }
        $prop = $this->cache->getItem(ImageUtils::CACHE_KEY)->get();
        $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

        //Sanitize the image name to be slugy friendly
        $namerOptions = ['namer' => $this->config['namer'], 'public_path' => $this->config['public_path']];
        $slug = $this->imageUtils->namer($originalName, $namerOptions);

        $imagePath = $image->getPathName();

        $imagesize = getimagesize($imagePath);
        list($imageWidth, $imageHeight) = $imagesize;

        $dimension = ['height' => $imageHeight, 'width' => $imageWidth];

        // Save original file
        if ($this->config['keep_original'] && !$this->config['use_js']) {
            $name = $slug['safename'].'.'.$image->guessExtension();
            $temp = tempnam('/tmp', 'foo');
            copy($imagePath, $temp);
            $mimeTypes = [$image->guessExtension()];
            $original_copy = new File($temp);
            $original_copy->originalName = $name;
            $data['original_file'] = $original_copy;
        }
        if ($this->config['keep_original'] && $this->config['use_js']) {
            /** @var UploadedFile */
            $original = $data['original_file'];
            $file = new File($original->getPathname());
            $name = $slug['safename'].'.'.$original->guessExtension();
            $file->originalName = $name;
            $data['original_file'] = $file;
            array_push($mimeTypes, $original->guessExtension());
        }

        // Point of no return. At this point the image become a webp file
        if (!empty(get_extension_funcs('gd')) && !$this->config['use_js'] && !$isWebp) {
            $callFunction = $this->imageUtils->createGdImg($image->guessExtension(), $imagePath);
            imagewebp($callFunction, $imagePath, $this->config['quality']);
            array_unshift($mimeTypes, $image->guessExtension());
            imagedestroy($callFunction);
        }

        // Set data to the given mapped inputs

        $data[$prop['name']] = $slug['safename'].'.'.$image->guessExtension();
        $data[$prop['slug']] = $slug['slug'];
        $data[$prop['dimension']] = json_encode(['dimension' => $dimension]);
        $data[$prop['mimeTypes']] = json_encode(['mimes' => $mimeTypes]);
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
                'attr' => ['data-type' => 'json_array', 'data-prop' => 'dimension'],
            ])
            ->add($attributes['alt'], TextType::class, [
                'mapped' => true,
                'required' => false,
                'data_class' => null,
                'attr' => ['data-name' => 'alt'],
                'row_attr' => ['class' => 'imc-alt'],
            ])
            ->add($attributes['mimeTypes'], HiddenType::class, ['mapped' => true,
             'data_class' => null,
             'attr' => ['data-type' => 'json_array', 'data-prop' => 'mimes'],
             ])
            ->add('image_converter', FileType::class, [
                'multiple' => false,
                'mapped' => false,
                'required' => false,
                'error_bubbling' => true,
                'attr' => [
                    'data-entity' => $attributes['entity'],
                    'data-relation' => $attributes['relation'],
                    'data-qual' => $this->config['quality'],
                    'data-name' => 'image_converter',
                    'accept' => 'image/*',
                    'data-prop' => $attributes['property'],
                ],
                'label_attr' => ['class' => 'imc-label'],
                'row_attr' => ['class' => 'imc-uploader-input'],
            ]);

        if ($this->config['keep_original']) {
            $form->add('original_file', FileType::class, ['mapped' => false, 'required' => false, 'multiple' => false]);
        }
        if ($attributes['relation']) {
            $form->add('entity_value', ImageChoiceType::class, [
                'mapped' => false,
                'attr' => ['class' => 'entity_value'],
                'class' => $attributes['entity'],
                'multiple' => $attributes['multiple'],
                'expanded' => $attributes['multiple'],
                'choice_label' => 'id',
                'error_bubbling' => true,
            ]);
        }
    }

    /**
     * Verify form,
     * Delete cache items,
     * Move image to the given config path.
     */
    public function onFormPostSubmit(FormEvent $event): void
    {
        $prop = $this->cache->getItem(ImageUtils::CACHE_KEY)->get();

        $form = $event->getForm();
        $this->cache->deleteItem(self::NAME_VAL);
        $key = $form->get($prop['name'])->getData();
        $this->cache->deleteItem(ImageUtils::CACHE_KEY);
        $this->cache->deleteItem(ImageUtils::ENTITY_CACHE_KEY);
        $image = $form->get('image_converter')->getData();
        if ($form->isSubmitted() && $form->isValid() && $image) {
            $path = $this->imageUtils->strAppendSlash($this->config['media_uploads_path']);
            if ($this->config['keep_original']) {
                /** @var File */
                $original_file = $form->get('original_file')->getData();
                $original_file->move($path, $original_file->originalName);
            }
            /* @var UploadedFile $image */
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
