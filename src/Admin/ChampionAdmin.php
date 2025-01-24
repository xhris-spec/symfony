<?php

declare(strict_types=1);

namespace App\Admin;

use App\Components\Form\Type\SlugType;
use App\Components\Form\Type\ImagePreviewType;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use App\Entity\Champion;

/**
 * @extends BaseAdmin<Champion>
 */
final class ChampionAdmin extends BaseAdmin
{
    protected $classnameLabel = 'Champions';

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('id', 'numeric', ['label' => 'ID'])
            ->addIdentifier('splashart', null, ['label' => 'Splashart', 'template' => 'admin/cover-list-item.html.twig'])
            ->addIdentifier('name', null, ['label' => 'Nombre del campeón'])
            ->addIdentifier('rol', null, ['label' => 'Rol', 'template' => 'admin/rol_list.html.twig'])
            ->add('active', null, ['label' => 'Activo', 'editable' => true])
        ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $object = $form->getAdmin()->getSubject();
        $roles = $object->getRol();

        $form
            ->tab('Champion')
                ->with('Dades', ['class' => 'col-md-4 col-sm-12 col-no-header'])
                        ->add('translations', TranslationsType::class, [
                            'label' => false,
                            'required_locales' => ['es', 'en'],
                            'fields' => [
                                'description' => [
                                    'field_type' => CKEditorType::class,
                                    'label' => 'language.description',
                                    'required' => true,
                                ],
                            ],
                        ])
                        ->add(
                            'splashart',
                            ImagePreviewType::class,
                            [
                                'required' => false,
                                'label' => false,
                                'btn_edit' => false,
                            ],
                            [
                                'link_parameters' => [
                                    'context' => 'default',
                                    'provider' => 'sonata.media.provider.image',
                                ],
                            ]
                        )
                ->end()
                ->with('Champion', ['class' => 'col-md-8 col-sm-12 col-no-header'])
                    ->add('name', null, ['label' => 'Nombre del campeón', 'required' => true])
                    ->add('slug', SlugType::class, ['label' => 'URL', 'field_from' => 'name'])
                    ->add('rol', ChoiceType::class, [
                        'label' => 'Rol',
                        'required' => true,
                        'multiple' => true,
                        'data' => $roles,
                        'choices' => [
                            'Assasin' => 'assasins',
                            'Fighter' => 'fighter',
                            'Mage' => 'mage',
                            'Marksmen' => 'marksmen',
                            'Support' => 'support',
                            'Tank' => 'tank',
                        ],
                    ])
                    ->add('active', null, ['label' => 'Activo', 'required' => false])

                ->end()
            ->end()
            ->tab('Habilidades')
                ->with('details', ['class' => 'col-md-12 col-sm-12 col-no-header'])
                    ->add(
                        'habilities',
                        CollectionType::class,
                        [
                            'required' => false,
                            'by_reference' => true,
                            'label' => false,
                            'help' => '<i class="fa fa-info-circle"></i>
                                Para borrar un elemento marca la columna “Borrar” del elemento que quieras borrar
                                y pulsa el botón “Actualizar” de la parte inferior.',
                            'help_html' => true,
                        ],
                        [
                            'edit' => 'inline',
                            'inline' => 'table',
                            'admin_code' => 'admin.hability',
                        ]
                    )
                ->end()
            ->end()
        ;
    }

    /**
     * @param Champion $object
     */
    public function toString(object $object): string
    {
        return 'Champion';
    }
}
