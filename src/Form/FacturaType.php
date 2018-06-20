<?php

namespace App\Form;

use App\Entity\Contrato;
use App\Entity\Factura;
use App\Entity\NomEstadoFactura;
use App\Entity\NomTipoServicio;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FacturaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contrato',HiddenType::class,array(
                'data'=>$options['id_contrato']
            ))
            ->add('contrato_datos',TextType::class,array(
                'label'=>'Contrato',
                'data'=>$options['contrato_datos'],
                'mapped'=>false,
                'disabled'=>true
            ))
            ->add('proveedor',TextType::class,array(
                'data'=>$options['proveedor'],
                'mapped'=>false,
                'disabled'=>true
            ))
            ->add('numeroRegistro',IntegerType::class,array(
                'label'=>'No. de Registro',
            ))
            ->add('numeroDelProveedor',TextType::class,array(
                'label'=>'No. de Factura del Proveedor',
            ))
            ->add('fecha')
            ->add('tipoServicio',ChoiceType::class, array(
                'choices'  => $options["tipos_de_servicios"],
                'expanded' => true,
                //'data'=>1
            ))
            ->add('concepto')
            ->add('valorCup',MoneyType::class,array(
                'currency'=>'CUP',
                ))
            ->add('valorCuc',MoneyType::class,array(
                'currency'=>'CUC',
                ))
            ->add('estado',ChoiceType::class, array(
                'choices'  => $options["estados_factura"],
                'expanded' => false,
                //'data'=>1
            ))
            ->add('numeroCheque')
            ->add('fechaCheque')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Factura::class,
            'id_contrato'=>null,
            'contrato_datos'=>null,
            'proveedor'=>null,
            'tipos_de_servicios'=>null,
            'estados_factura'=>null
        ]);
    }
}
