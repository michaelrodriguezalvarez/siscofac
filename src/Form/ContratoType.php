<?php

namespace App\Form;

use App\Entity\Contrato;
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
            ->add('proveedor')
            ->add('tipoDeServicio')
            ->add('objeto')
            ->add('nit')
            ->add('tipoDePersona')
            ->add('cuentaBancariaCup')
            ->add('cuentaBancariaCuc')
            ->add('valorContratoCup')
            ->add('valorContratoCuc')
            ->add('ejecucionContratoCup')
            ->add('ejecucionContratoCuc')
            ->add('saldoCup')
            ->add('saldoCuc')
            ->add('banco')
            ->add('aprobContratoComiteContratacion')
            ->add('aprobContratoComiteAdministracion')
            ->add('areaAdministraContrato')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contrato::class,
        ]);
    }
}
