<?php

namespace Onadrog\ImageConverterBundle\Type;

use Onadrog\ImageConverterBundle\EventSubscriber\ImageConverterSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageConverterType extends AbstractType
{
    public function __construct(
        private array $config,
        private ImageConverterSubscriber $imageConverterSubscriber
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'mapped' => true,
            'label' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'image_converter';
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('image', FileType::class, [
            'mapped' => false,
            'required' => $options['required'] ?? true,
            'multiple' => false,
           /*  'constraints' => [
                new File([
                    'maxSize' => $this->config['max_size'] . 'k',
                    'mimeTypes' => [
                        'image/*'
                    ],
                    'mimeTypesMessage' => 'Please insert an image.'
                ])
            ], */
        ])->addEventSubscriber($this->imageConverterSubscriber);
    }
}
