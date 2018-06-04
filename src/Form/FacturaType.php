<?php

namespace App\Form;

use App\Entity\Contrato;
use App\Entity\Factura;
use App\Entity\NomEstadoFactura;
use App\Entity\NomTipoServicio;
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
            ->add('contrato',EntityType::class, array(
                'class' => Contrato::class,
                'choice_label'=>'numero'
            ))
            ->add('proveedor',TextType::class,array(
                'mapped'=>false,
                'disabled'=>true
            ))
            ->add('numero',IntegerType::class,array(
                'label'=>'No. de Factura',
            ))
            ->add('fecha')
            ->add('tipoServicio',EntityType::class, array(
                'class' => NomTipoServicio::class,
                'choice_label'=>'nombre'
            ))
            ->add('concepto')
            ->add('valorCup')
            ->add('valorCuc')
            ->add('estado',EntityType::class, array(
                'class' => NomEstadoFactura::class,
                'choice_label'=>'estado'
            ))
            ->add('numeroCheque')
            ->add('fechaCheque')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Factura::class,
        ]);
    }
}
