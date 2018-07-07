<?php

namespace App\Form;

use App\Entity\ConfAplicacion;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfAplicacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombreAplicacion')
            ->add('slogan')
            ->add('idiomaPorDefecto', ChoiceType::class, array(
                'choices' => array(
                    'Español' => 'es' ,
                    'English' => 'en', )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ConfAplicacion::class,
        ]);
    }
}