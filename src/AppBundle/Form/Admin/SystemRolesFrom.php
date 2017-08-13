<?php

namespace AppBundle\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SystemRolesFrom extends AbstractType
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
            ->add('roleName', \Symfony\Component\Form\Extension\Core\Type\TextType::class, array(
                'label' => 'Role Name',
                'required' => FALSE
            ))
            ->add('roleStatus', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, array(
                'label' => 'Role Status',
                'choices' => array( 'Unpblish' => 0, 'Publish' => 1)
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
     * @return [type]
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'    => '\AppBundle\Entity\SystemRolesEntity'
        ));
    }

    /**
     * @return [string]
     */
    public function getName()
    {
        return 'SystemRoles';
    }

}
