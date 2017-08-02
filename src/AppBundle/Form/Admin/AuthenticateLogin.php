<?php

namespace AppBundle\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthenticateLogin extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('csrf_token', \Symfony\Component\Form\Extension\Core\Type\HiddenType::class,array(
                //'data' => $this->get('security.csrf.token_manager')->refreshToken('user-login')
                'data' => ''
            ))
            ->add('username', \Symfony\Component\Form\Extension\Core\Type\TextType::class)
            ->add('password', \Symfony\Component\Form\Extension\Core\Type\PasswordType::class)
            ->add('remember', \Symfony\Component\Form\Extension\Core\Type\CheckboxType::class , array(
                'label'    => '',
                'required' => false,
            ));
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => '',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'csrf_token_id'   => 'task_item',
        ));
    }

    public function getName()
    {
        return 'authenticateLogin';
    }



}
