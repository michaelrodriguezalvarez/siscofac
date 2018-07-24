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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
                'disabled'=>true,
                'attr'=>array('size'=>'10')
            ))
            ->add('proveedor',TextType::class,array(
                'data'=>$options['proveedor'],
                'mapped'=>false,
                'disabled'=>true
            ))
            ->add('numeroRegistro',TextType::class,array(
                'label'=>'No. de Registro',
                'attr'=>array('size'=>'5')
            ))
            ->add('numeroDelProveedor',TextType::class,array(
                'label'=>'No. de Factura del Proveedor',
                'attr'=>array('size'=>'5')
            ))
            ->add('fecha', TextType::class, array(
                'attr'=> array('size'=>'8'),
                'data'=>$options['fechaEditar']
            ))
            ->add('tipoServicio',ChoiceType::class, array(
                'choices'  => $options["tipos_de_servicios"],
                'expanded' => true,
                //'data'=>1
            ))
            ->add('concepto',TextareaType::class, array(
                'attr' => array('class' => 'form-control'),
            ))
            ->add('valorCup',MoneyType::class,array(
                'currency'=>'CUP',
                'attr'=>array('size'=>'10')
                ))
            ->add('valorCuc',MoneyType::class,array(
                'currency'=>'CUC',
                'attr'=>array('size'=>'10')
                ))
            ->add('estado',ChoiceType::class, array(
                'choices'  => $options["estados_factura"],
                'expanded' => false,
                //'data'=>1
            ))
            ->add('numeroCheque',TextType::class,array(
                'label'=>'No. de Cheque',
            ))
            ->add('fechaCheque', TextType::class, array(
                'attr'=> array('size'=>'8'),
                'data'=>$options['fechaChequeEditar']
            ))
            ->add('pagado_anteriormente',HiddenType::class,array(
                'data'=>$options['pagado_anteriormente'],
                'mapped'=>false,
            ))
            ->add('pagado_anteriormente',HiddenType::class,array(
                'data'=>$options['pagado_anteriormente'],
                'mapped'=>false,
            ))
            ->add('valor_anterior_cup',HiddenType::class,array(
                'data'=>$options['valor_anterior_cup'],
                'mapped'=>false,
            ))
            ->add('valor_anterior_cuc',HiddenType::class,array(
                'data'=>$options['valor_anterior_cuc'],
                'mapped'=>false,
            ))
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
            'estados_factura'=>null,
            'pagado_anteriormente'=>null,
            'valor_anterior_cup'=>null,
            'valor_anterior_cuc'=>null,
            'fechaEditar'=>null,
            'fechaChequeEditar'=>null,
        ]);
    }
}
