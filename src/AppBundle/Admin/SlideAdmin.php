<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 13.12.15
 * Time: 17:58
 */


namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class SlideAdmin extends Admin
{
    protected $datagridValues = array(
        '_sort_order' => 'ASC',
        '_sort_by' => 'order',
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $object = $this->getSubject();

        $formMapper
            ->with('Основное')
            ->add('file', 'file', [
                'required' => !$object->getWebPath(),
                'help' => $object->getWebPath() ? sprintf('<img src="%s" class="form-preview">', $object->getWebPath()) : '',
            ])
            ->add('visible', null, ['required' => false])
            ->add('order')
            ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('visible');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('file', null, ['template' => 'AppBundle:Admin:list_field_image.html.twig'])
            ->add('visible', null, ['editable' => true])
            ->add('order', null, ['editable' => true])
            ->add('_action', 'actions', [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ]
            ]);
    }
}