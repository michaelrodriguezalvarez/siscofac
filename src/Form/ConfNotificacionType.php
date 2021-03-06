<?php

namespace App\Form;

use App\Entity\ConfNotificacion;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfNotificacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('correoNombre',TextType::class,array(
                'attr'=>array('placeholder'=>'Nombre')
            ))
            ->add('correoDireccion',EmailType::class,array(
                'attr'=>array('placeholder'=>'direccion@servidor.dominio')
            ))
            ->add('correoServidor',TextType::class,array(
                'attr'=>array('placeholder'=>'127.0.0.1')
            ))
            ->add('correoPuerto',NumberType::class,array(
                'attr'=>array('placeholder'=>'25')
            ))
            ->add('correoClave',PasswordType::class)
            ->add('correoAsunto',TextType::class,array(
                'attr'=>array('placeholder'=>'Asunto')
            ))
            ->add('correoTextoContratoInhabilitado',TextareaType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder'=>'Texto del correo del contrato inhabilitado'
                ),
            ))
            ->add('ayudaCorreoTextoContratoInhabilitado',ButtonType::class, array(
                'attr' => array(
                    'class' => 'btn btn-sm btn-warning',
                    'data-toggle'=>'modal',
                    'data-target'=>'#modalAyudaCorreoTextoContratoInhabilitado'
                ),
            ))
            ->add('correoTextoSaldoMinimo',TextareaType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder'=>'Texto del correo del contrato con saldo minimo'
                ),

            ))
            ->add('ayudaCorreoTextoSaldoMinimo',ButtonType::class, array(
                'attr' => array(
                    'class' => 'btn btn-sm btn-warning',
                    'data-toggle'=>'modal',
                    'data-target'=>'#modalAyudaCorreoTextoSaldoMinimo'
                ),
            ))
            ->add('correoTextoTiempoMinimo',TextareaType::class, array(
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder'=>'Texto del correo del contrato con tiempo minimo'
                ),
            ))
            ->add('ayudaCorreoTextoTiempoMinimo',ButtonType::class, array(
                'attr' => array(
                    'class' => 'btn btn-sm btn-warning',
                    'data-toggle'=>'modal',
                    'data-target'=>'#modalAyudaCorreoTextoTiempoMinimo'
                ),
            ))
            ->add('diasMinimoNotificacion',IntegerType::class,array(
                'attr'=>array('placeholder'=>'30')
            ))
            ->add('saldoMinimoNotificacionCup',MoneyType::class,array(
                'currency'=>'CUP',
                'attr'=>array('placeholder'=>'100')
            ))
            ->add('saldoMinimoNotificacionCuc',MoneyType::class,array(
                'currency'=>'CUC',
                'attr'=>array('placeholder'=>'100')
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ConfNotificacion::class,
        ]);
    }
}
