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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContratoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero', TextType::class, array(
                'attr'=>array('size'=>'5')
            ))
            ->add('anno', ChoiceType::class, array(
                'choices'  => $options["ultimos_annos_hasta_actual"],
            ))
            ->add('fechaInicio', TextType::class, array(
                'attr'=> array('size'=>'8')
            ))
            ->add('fechaTerminacion', TextType::class, array(
                'attr'=> array('size'=>'8')
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
            ->add('nit',TextType::class,array(
                "attr"=>array(
                    'placeholder'=>'00000000000'
                )
            ))
            ->add('reeup',TextType::class,array(
                "attr"=>array(
                    'placeholder'=>'00000000000'
                )
            ))
            ->add('carnetIdentidad',TextType::class,array(
                "attr"=>array(
                    'placeholder'=>'00000000000'
                )
            ))
            ->add('tipoDePersona',ChoiceType::class, array(
                'choices'  => $options["tipos_de_persona"],
                'expanded' => true,
                //'data'=>1
            ))
            ->add('cuentaBancariaCup',TextType::class,array(
                "attr"=>array(
                    'placeholder'=>'00000000000000000000'
                )
            ))
            ->add('cuentaBancariaCuc',TextType::class,array(
                "attr"=>array(
                    'placeholder'=>'00000000000000000000'
                )
            ))
            ->add('valorContratoInicialCup',MoneyType::class,array(
                'currency'=>'CUP',
                'attr'=>array('size'=>'10')
            ))
            ->add('valorContratoInicialCuc',MoneyType::class,array(
                'currency'=>'CUC',
                'attr'=>array('size'=>'10')

            ))
            ->add('valorContratoTotalCup',MoneyType::class,array(
                'currency'=>'CUP',
                'attr'=>array('size'=>'10')
            ))
            ->add('valorContratoTotalCuc',MoneyType::class,array(
                'currency'=>'CUC',
                'attr'=>array('size'=>'10')
            ))
            ->add('ejecucionContratoCup',MoneyType::class,array(
                'currency'=>'CUP',
                'attr'=>array('size'=>'10')
            ))
            ->add('ejecucionContratoCuc',MoneyType::class,array(
                'currency'=>'CUC',
                'attr'=>array('size'=>'10')
            ))
            ->add('saldoCup',MoneyType::class,array(
                'currency'=>'CUP',
                'attr'=>array('size'=>'10'),
            ))
            ->add('saldoCuc',MoneyType::class,array(
                'currency'=>'CUC',
                'attr'=>array('size'=>'10')
            ))
            ->add('banco',ChoiceType::class, array(
                'choices' => $options["bancos"],                
            ))
            ->add('formaDePago', TextType::class, array(
                'attr'=>array('size'=>'30')
            ))
            ->add('numeroAprobContratoComiteContratacion', TextType::class, array(
                'attr'=>array('size'=>'5')
            ))
            ->add('fechaAprobContratoComiteContratacion', TextType::class, array(
                'attr'=> array('size'=>'8')
            ))
            ->add('numeroAprobContratoComiteAdministracion', TextType::class, array(
                'attr'=>array('size'=>'5')
            ))
            ->add('fechaAprobContratoComiteAdministracion', TextType::class, array(
                'attr'=> array('size'=>'8')
            ))
            ->add('areaAdministraContrato',ChoiceType::class, array(
                'choices'  => $options["areas_administra_contrato"],
            ))
            ->add('estado', ChoiceType::class, array(
                'choices' => array(
                    'Activo' => 1 ,
                    'Inactivo' => 0, )
            ))
            ->add('motivoEstado',TextareaType::class, array(
                'attr' => array('class' => 'form-control'),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contrato::class,
            'ultimos_annos_hasta_actual' => null,
            'proveedores'=>null,            
            'tipos_de_servicios'=>null,
            'tipos_de_persona'=>null,
            'bancos'=>null,
            'areas_administra_contrato'=>null,
        ]);
    }
}
