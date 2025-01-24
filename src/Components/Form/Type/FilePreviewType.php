<?php

declare(strict_types=1);

namespace App\Components\Form\Type;

use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\MediaBundle\Model\MediaManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class FilePreviewType extends AbstractType
{
    public function __construct(
        protected MediaManagerInterface $mediaManager,
        protected ?string $file_label = null,
        protected ?bool $display_name = true
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'file_label' => $this->file_label,
            'display_name' => $this->display_name,
        ]);

        $resolver->setAllowedTypes('file_label', ['null', 'string']);
        $resolver->setAllowedTypes('display_name', ['null', 'bool']);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $fileId = $form->getData();
        $file = null;

        if ($fileId !== null) {
            $file = $this->mediaManager->findOneBy(['id' => $fileId]);
        }

        $view->vars['file'] = $file;
        $view->vars['file_label'] = $options['file_label'];
        $view->vars['display_name'] = $options['display_name'];
    }

    public function getName(): string
    {
        return 'FilePreview';
    }

    public function getParent(): string
    {
        return ModelListType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'file_preview';
    }
}
