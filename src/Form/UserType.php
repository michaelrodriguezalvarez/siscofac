<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('apellidos')
            ->add('username')
            ->add('password',PasswordType::class,array(
                'label'=>'Clave'
            ))
            ->add('email')
            ->add('roles', ChoiceType::class, array(
                'choices' => array(
                    'Administrador/a' => 'ROLE_ADMINISTRADOR' ,
                    'Jurídico/a' => 'ROLE_JURIDICO',
                    'Económico/a' => 'ROLE_ECONOMICO',
                    'Consultor/a' => 'ROLE_CONSULTOR',
                ),
                'multiple' => true
            ))
            ->add('isActive', CheckboxType::class,array(
                'label'=>'Activo',
                'attr'=>array('checked'=>'checked'),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
