<?php

namespace App\Form;

use App\Entity\Suplemento;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SuplementoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contrato',HiddenType::class,array(
                'data'=>$options['id_contrato']
            ))
            ->add('numero')
            ->add('objeto',TextareaType::class, array(
                'attr' => array('class' => 'form-control'),
            ))
            ->add('valorSuplementoCup',MoneyType::class,array(
                'currency'=>'CUP',
            ))
            ->add('valorSuplementoCuc',MoneyType::class,array(
                'currency'=>'CUC',
            ))
            ->add('fechaInicio')
            ->add('fechaTerminacion')
            ->add('numeroAcuerdo')
            ->add('fechaAcuerdo')


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Suplemento::class,
            'id_contrato'=>null
        ]);
    }
}
