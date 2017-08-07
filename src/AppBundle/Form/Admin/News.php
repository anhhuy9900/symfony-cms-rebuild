<?php

namespace AppBundle\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class News extends AbstractType
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
            ->add('category', \Symfony\Bridge\Doctrine\Form\Type\EntityType::class, array(
                'label' => 'Category ID',
                'class' => 'AppBundle:CategoriesNewsEntity',
                'choice_label' => 'name'
            ))
            ->add('name', \Symfony\Component\Form\Extension\Core\Type\TextType::class, array(
                'label' => 'Name'
            ))
            ->add('file', \Symfony\Component\Form\Extension\Core\Type\FileType::class, array(
                'label' => 'Image',
                'required' => FALSE
            ))
            ->add('description', \Symfony\Component\Form\Extension\Core\Type\TextareaType::class, array(
                'label' => 'Description'
            ))
            ->add('content', \Symfony\Component\Form\Extension\Core\Type\TextareaType::class, array(
                'label' => 'Content'
            ))
            ->add('status', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, array(
                'label' => 'Status',
                'choices' => array( 0 => 'Unpblish', 1 => 'Publish')
            ))
//            ->add('tags', \Symfony\Component\Form\Extension\Core\Type\TextType::class, array(
//                'label' => 'Tags input',
//                'data' =>  '',
//                'required' => FALSE
//            ))
//            ->add('lists_thumb', \Symfony\Component\Form\Extension\Core\Type\TextareaType::class, array(
//                'data' => $options['galleries'],
//                'required' => FALSE
//            ))
//            ->add('lists_del_file', \Symfony\Component\Form\Extension\Core\Type\TextareaType::class, array(
//                'data' => '',
//                'required' => FALSE
//            ))
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
            'data_class'    => '\AppBundle\Entity\NewsEntity',
            'galleries'     => [],
            'tags'          => [],
        ));
    }

    /**
     * @return [string]
     */
    public function getName()
    {
        return 'News';
    }

}
