<?php

declare(strict_types=1);

namespace App\Admin;

use App\Components\Form\Type\ImagePreviewType;
use App\Components\Form\Type\FilePreviewType;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use App\Entity\Hability;

/**
 * @extends BaseAdmin<Hability>
 */
final class HabilityAdmin extends BaseAdmin
{
    protected $classnameLabel = 'Habilities';

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('id', 'numeric', ['label' => 'ID'])
            ->addIdentifier('name', null, ['label' => 'Nombre de la habilidad'])
            ->addIdentifier('layout', null, ['label' => 'Tecla de uso'])
            ->addIdentifier('image', null, ['label' => 'Imagen de la habilidad', 'template' => 'admin/image-list-item.html.twig'])
            ->addIdentifier('champion', null, ['label' => 'Nombre del campeón'])

        ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('Champion', ['class' => 'col-md-8'])
                ->add('translations', TranslationsType::class, [
                    'label' => false,
                    'fields' => [
                        'name' => [
                            'field_type' => null,
                            'label' => 'Nombre de la habilidad',
                            'required' => true,
                        ],
                        'description' => [
                            'field_type' => CKEditorType::class,
                            'label' => 'Descripción de la habilidad',
                            'required' => true,
                        ],
                    ],
                ])
                // ->add('champion', null, ['label' => 'Champion'])
                ->add('image',
                    ImagePreviewType::class,
                    [
                        'required' => true,
                        'label' => 'Imagen de la habilidad',
                        'image_size' => 'medium',
                        'btn_delete' => null,
                        'btn_edit' => null,
                        'help_html' => true,
                    ],
                    [
                        'link_parameters' => [
                            'context' => 'default',
                            'provider' => 'sonata.media.provider.image',
                        ],
                    ])
            ->end()
            ->with('Dades', ['class' => 'col-md-4'])
                ->add('layout', ChoiceType::class, [
                    'choices' => [
                        '-' => null,
                        'Passive' => 'passive',
                        'Q' => 'q',
                        'E' => 'e',
                        'W' => 'w',
                        'R' => 'r',
                    ],
                ])
                ->add(
                    'video',
                    FilePreviewType::class,
                    [
                        'required' => false,
                        'label' => 'Video de la habilidad',
                        'btn_edit' => false,
                    ],
                    [
                        'link_parameters' => [
                            'context' => 'default',
                            'provider' => 'sonata.media.provider.file',
                        ],
                    ]
                )
            ->end()
        ;
    }

    /**
     * @param Hability $object
     */
    public function toString(object $object): string
    {
        return 'Hability';
    }
}
