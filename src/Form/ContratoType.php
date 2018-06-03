<?php

namespace App\Form;

use App\Entity\Contrato;
use App\Entity\NomProveedor;
use App\Entity\NomTipoServicio;
use App\Entity\NomTipoPersona;
use App\Entity\NomBanco;
use App\Entity\Acuerdo;
use App\Entity\NomArea;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContratoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero')
            ->add('anno', ChoiceType::class, array(
                'choices'  => $this->getUltimosNAnnosHastaActual(10)
            ))
            ->add('fechaInicio')
            ->add('fechaTerminacion')
            ->add('proveedor',EntityType::class, array(
                'class' => NomProveedor::class,
                'choice_label'=>'nombre'
            ))
            ->add('tipoDeServicio',EntityType::class, array(
                'class' => NomTipoServicio::class,
                'choice_label'=>'nombre',
                'expanded' => true,
            ))
            ->add('objeto',TextareaType::class, array(
                'attr' => array('class' => 'form-control'),
            ))
            ->add('nit')
            ->add('reeup')
            ->add('carnetIdentidad')
            ->add('tipoDePersona',EntityType::class, array(
                'class' => NomTipoPersona::class,
                'choice_label'=>'nombre',
                'expanded' => true,
            ))
            ->add('cuentaBancariaCup')
            ->add('cuentaBancariaCuc')
            ->add('valorContratoInicialCup')
            ->add('valorContratoInicialCuc')
            ->add('valorContratoTotalCup',TextType::class,array(
                'disabled'=>true
            ))
            ->add('valorContratoTotalCuc',TextType::class,array(
                'disabled'=>true
            ))
            ->add('ejecucionContratoCup')
            ->add('ejecucionContratoCuc')
            ->add('saldoCup',TextType::class,array(
                'disabled'=>true
            ))
            ->add('saldoCuc',TextType::class,array(
                'disabled'=>true
            ))
            ->add('banco',EntityType::class, array(
                'class' => NomBanco::class,
                'choice_label'=>'nombre'
            ))
            ->add('aprobContratoComiteContratacion',EntityType::class, array(
                'class' => Acuerdo::class,
            ))
            ->add('aprobContratoComiteAdministracion',EntityType::class, array(
                'class' => Acuerdo::class,
            ))
            ->add('areaAdministraContrato',EntityType::class, array(
                'class' => NomArea::class,
                'choice_label'=>'nombre'
            ))
            ->add('estado', ChoiceType::class, array(
                'choices' => array(
                    'Activo' => 1 ,
                    'Inactivo' => 0, )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contrato::class,
        ]);
    }

    public function getUltimosNAnnosHastaActual($n):array{
        $anno_actual = date('Y');
        $annos = array();

        while ($n >= 0) {
            array_unshift($annos, $anno_actual - $n);
            $n--;
        }

        return array_combine($annos, $annos);
    }
}
