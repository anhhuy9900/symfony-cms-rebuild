<?php

namespace AppBundle\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SystemModules extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('csrf_token', \Symfony\Component\Form\Extension\Core\Type\HiddenType::class,array(
                //'data' => $this->get('security.csrf.token_manager')->refreshToken('user-login')
                'data' => ''
            ))
            ->add('id', \Symfony\Component\Form\Extension\Core\Type\HiddenType::class, array(
               // 'data' => $options['id'],
            ))
            ->add('parentId', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, array(
                'label' => 'Parent',
                //'choices' => $options['recursiveModules'],
                //'data' => $options['parentId']
            ))
            ->add('moduleName', \Symfony\Component\Form\Extension\Core\Type\TextType::class, array(
                'label' => 'Module Name',
                //'data' => $options['moduleName']
            ))
            ->add('moduleAlias', \Symfony\Component\Form\Extension\Core\Type\TextType::class, array(
                'label' => 'Module Alias',
                //'data' => $options['moduleAlias'],
                'required' => FALSE
            ))
            ->add('moduleOrder', \Symfony\Component\Form\Extension\Core\Type\TextType::class, array(
                'label' => 'Module Order',
                //'data' => $options['moduleOrder']
            ))
            ->add('moduleStatus', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, array(
                'label' => 'Module Status',
                //'data' => $options['moduleStatus'],
                'choices' => array( 'Publish' => 1, 'Unpblish' => 0)
            ))
            ->add('send', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class, array(
                'label' => 'Submit',
            ));
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            //'data_class'        => 'AppBundle\Entity\SystemModulesEntity',
            'recursiveModules'  => [],
            'csrf_protection'   => true,
            'csrf_field_name'   => '_token',
            // a unique key to help generate the secret token
            'csrf_token_id'     => 'task_item',
        ));
    }

    public function getName()
    {
        return 'authenticateLogin';
    }



}
