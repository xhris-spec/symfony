<?php

declare(strict_types=1);

namespace App\Components\Form\Type;

use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\MediaBundle\Model\MediaManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class ImagePreviewType extends AbstractType
{
    public function __construct(
        protected MediaManagerInterface $mediaManager,
        protected ?string $image_label = null,
        protected ?string $image_size = 'medium',
        protected ?bool $display_name = true
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'image_label' => $this->image_label,
            'image_size' => $this->image_size,
            'display_name' => $this->display_name,
        ]);

        $resolver->setAllowedTypes('image_label', ['null', 'string']);
        $resolver->setAllowedTypes('image_size', ['null', 'string']);
        $resolver->setAllowedTypes('display_name', ['null', 'bool']);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $imageId = $form->getData();
        $image = null;

        if ($imageId !== null) {
            $image = $this->mediaManager->findOneBy(['id' => $imageId]);
        }

        $view->vars['image'] = $image;
        $view->vars['image_label'] = $options['image_label'];
        $view->vars['image_size'] = $options['image_size'];
        $view->vars['display_name'] = $options['display_name'];
    }

    public function getName(): string
    {
        return 'ImagePreview';
    }

    public function getParent(): string
    {
        return ModelListType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'image_preview';
    }
}
