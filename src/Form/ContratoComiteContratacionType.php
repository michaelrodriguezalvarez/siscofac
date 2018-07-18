<?php

namespace App\Form;

use App\Entity\ContratoComiteContratacion;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContratoComiteContratacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('orden',TextType::class, array(
                'data'=> $options["orden"]
            ))
            ->add('proveedor',ChoiceType::class, array(
                'choices' => $options["proveedores"],
            ))
            ->add('tipoDeServicio',ChoiceType::class, array(
                'choices'  => $options["tipos_de_servicios"],
                'expanded' => true,
                //'data'=>1
            ))
            ->add('objeto',TextareaType::class, array(
                'attr' => array('class' => 'form-control'),
            ))
            ->add('valorContratoInicialCup',MoneyType::class,array(
                'currency'=>'CUP',
            ))
            ->add('valorContratoInicialCuc',MoneyType::class,array(
                'currency'=>'CUC',
            ))
            ->add('areaAdministraContrato',ChoiceType::class, array(
                'choices'  => $options["areas_administra_contrato"],
            ))
            ->add('fechaDeReunion', TextType::class, array(
                'attr'=> array('size'=>'8')
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContratoComiteContratacion::class,
            'proveedores'=>null,
            'tipos_de_servicios'=>null,
            'areas_administra_contrato'=>null,
            'orden'=>null,
        ]);
    }
}
