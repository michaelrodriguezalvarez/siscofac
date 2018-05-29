<?php

namespace App\Form;

use App\Entity\Contrato;
use App\Entity\NomProveedor;
use App\Entity\NomTipoServicio;
use App\Entity\NomTipoPersona;
use App\Entity\NomBanco;
use App\Entity\Acuerdo;
use App\Entity\NomArea;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContratoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero')
            ->add('anno')
            ->add('fechaInicio')
            ->add('fechaTerminacion')
            ->add('proveedor',EntityType::class, array(
                'class' => NomProveedor::class,
                'choice_label'=>'provincia'
            ))
            ->add('tipoDeServicio',EntityType::class, array(
                'class' => NomTipoServicio::class,
                'choice_label'=>'nombre'
            ))
            ->add('objeto')
            ->add('nit')
            ->add('tipoDePersona',EntityType::class, array(
                'class' => NomTipoPersona::class,
                'choice_label'=>'nombre'
            ))
            ->add('cuentaBancariaCup')
            ->add('cuentaBancariaCuc')
            ->add('valorContratoCup')
            ->add('valorContratoCuc')
            ->add('ejecucionContratoCup')
            ->add('ejecucionContratoCuc')
            ->add('saldoCup')
            ->add('saldoCuc')
            ->add('banco',EntityType::class, array(
                'class' => NomBanco::class,
                'choice_label'=>'nombre'
            ))
            ->add('aprobContratoComiteContratacion')
            ->add('aprobContratoComiteContratacion',EntityType::class, array(
                'class' => Acuerdo::class,
                'choice_label'=>'numero'
            ))
            ->add('aprobContratoComiteAdministracion')
            ->add('aprobContratoComiteAdministracion',EntityType::class, array(
                'class' => Acuerdo::class,
                'choice_label'=>'numero'
            ))
            ->add('areaAdministraContrato',EntityType::class, array(
                'class' => NomArea::class,
                'choice_label'=>'nombre'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contrato::class,
        ]);
    }
}
