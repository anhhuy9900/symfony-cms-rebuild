<?php

namespace AppBundle\Form\Front;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegister extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('csrf_token', \Symfony\Component\Form\Extension\Core\Type\HiddenType::class,array(
                //'data' => $this->get('security.csrf.token_manager')->refreshToken('user-login')
                'data' => ''
            ))
            ->add('fullname', \Symfony\Component\Form\Extension\Core\Type\TextType::class)
            ->add('email', \Symfony\Component\Form\Extension\Core\Type\TextType::class)
            ->add('password', \Symfony\Component\Form\Extension\Core\Type\PasswordType::class)
            ->add('confirm_password', \Symfony\Component\Form\Extension\Core\Type\PasswordType::class)
            ->add('phone', \Symfony\Component\Form\Extension\Core\Type\TextType::class)
            ->add('address', \Symfony\Component\Form\Extension\Core\Type\TextareaType::class)
            ->add('gender', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, array(
                'choices'  => array(
                    'Male' => 1,
                    'Female' => 2,
                ),
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
        return 'userLoginForm';
    }



}