<?php

namespace App\Form;

use App\Entity\Suplemento;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('numero', TextType::class, array(
                'attr'=>array('size'=>'5')
            ))
            ->add('objeto',TextareaType::class, array(
                'attr' => array('class' => 'form-control'),
            ))
            ->add('valorSuplementoCup',MoneyType::class,array(
                'currency'=>'CUP',
                'attr'=>array('size'=>'10')
            ))
            ->add('valorSuplementoCuc',MoneyType::class,array(
                'currency'=>'CUC',
                'attr'=>array('size'=>'10')
            ))
            ->add('fechaInicio', TextType::class, array(
                'attr'=> array('size'=>'8'),
                'data'=>$options['fechaInicioEditar']
            ))
            ->add('fechaTerminacion', TextType::class, array(
                'attr'=> array('size'=>'8'),
                'data'=>$options['fechaTerminacionEditar']
            ))
            ->add('numeroAcuerdo', TextType::class, array(
                'attr'=>array('size'=>'5')
            ))
            ->add('fechaAcuerdo', TextType::class, array(
                'attr'=> array('size'=>'8'),
                'data'=>$options['fechaAcuerdoEditar']
            ))


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Suplemento::class,
            'id_contrato'=>null,
            'fechaInicioEditar'=>null,
            'fechaTerminacionEditar'=>null,
            'fechaAcuerdoEditar'=>null
        ]);
    }
}
