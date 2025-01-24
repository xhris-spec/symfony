<?php

declare(strict_types=1);

namespace App\Components\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class SlugType extends AbstractType
{
    public function __construct(private readonly string $field_from = '', private readonly ?string $field_from_text = null)
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'field_from' => $this->field_from,
            'field_from_text' => $this->field_from_text,
        ]);

        $resolver->setAllowedTypes('field_from', ['null', 'string']);
        $resolver->setAllowedTypes('field_from_text', ['null', 'string']);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['field_from'] = $options['field_from'];
        $view->vars['field_from_text'] = $options['field_from_text'] ??
            $view->parent->children[$options['field_from']]->vars['label'] ??
            $options['field_from']
        ;
    }

    public function getName(): string
    {
        return 'Slug';
    }

    public function getParent(): string
    {
        return TextType::class;
    }
}
