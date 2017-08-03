<?php

namespace AppBundle\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SystemModules extends AbstractType
{
    /**
     * @param  FormBuilderInterface
     * @param  array
     * @return [type]
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('csrf_token', \Symfony\Component\Form\Extension\Core\Type\HiddenType::class,array(
            //     //'data' => $this->get('security.csrf.token_manager')->refreshToken('user-login')
            //     'data' => ''
            // ))
            // ->add('id', \Symfony\Component\Form\Extension\Core\Type\HiddenType::class, array(
            //    //'data' => $options['id'],
            // ))
            ->add('parentId', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, array(
                'label' => 'Parent',
                'choices' => $options['recursiveModules'],
            ))
            ->add('moduleName', \Symfony\Component\Form\Extension\Core\Type\TextType::class, array(
                'label' => 'Module Name',
                'required' => FALSE
            ))
            ->add('moduleAlias', \Symfony\Component\Form\Extension\Core\Type\TextType::class, array(
                'label' => 'Module Alias',
                'required' => FALSE
            ))
            ->add('moduleOrder', \Symfony\Component\Form\Extension\Core\Type\TextType::class, array(
                'label' => 'Module Order',
            ))
            ->add('moduleStatus', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, array(
                'label' => 'Module Status',
                'choices' => array( 'Publish' => 1, 'Unpblish' => 0)
            ))
            ->add('send', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class, array(
                'label' => 'Submit',
            ));
    }

    /**
     * @param OptionsResolver
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array());
    }

    /**
     * @param  OptionsResolver
     * @return [object]
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'    => '\AppBundle\Entity\SystemModulesEntity',
            // 'id' => NULL,
            // 'parentId' => NULL,
            // 'moduleName' => NULL,
            // 'moduleAlias' => NULL,
            // 'moduleOrder' => NULL,
            // 'moduleStatus' => NULL,
            'recursiveModules' => [],
        ));
    }

    /**
     * @return [string]
     */
    public function getName()
    {
        return 'SystemModules';
    }

}
