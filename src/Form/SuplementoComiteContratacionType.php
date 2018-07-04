<?php

namespace App\Form;

use App\Entity\SuplementoComiteContratacion;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SuplementoComiteContratacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contrato',HiddenType::class)
            ->add('ruta',HiddenType::class,array(
                'mapped'=>false,
                'data'=>$options["ruta"],
            ))
            ->add('contrato_numero',TextType::class,array(
                'label'=>'No. Contrato',
                'mapped'=>false,
                "attr"=>array(
                    'placeholder'=>'No.'
                )
            ))
            ->add('contrato_anno',ChoiceType::class, array(
                'label'=>'Año de Contrato',
                'mapped'=>false,
                'choices' => $options["ultimos_annos_hasta_actual"],
            ))
            ->add('contrato_proveedor',TextType::class, array(
                'label'=>'Proveedor',
                'mapped'=>false,
                'disabled'=>true
            ))
            ->add('numero')
            ->add('objeto',TextareaType::class, array(
                'attr' => array('class' => 'form-control'),
            ))
            ->add('valorCup',MoneyType::class,array(
                'currency'=>'CUP',
            ))
            ->add('valorCuc',MoneyType::class,array(
                'currency'=>'CUC',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SuplementoComiteContratacion::class,
            'ultimos_annos_hasta_actual'=>null,
            'ruta'=>null
        ]);
    }
}
