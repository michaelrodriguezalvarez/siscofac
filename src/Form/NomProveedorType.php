<?php

namespace App\Form;

use App\Entity\NomProveedor;
use App\Entity\NomProvincia;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NomProveedorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('provincia',EntityType::class, array(
                'class' => NomProvincia::class,
                'choice_label'=>'nombre'
            ))
            ->add('direccion')
            ->add('telefono')
            ->add('correo')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NomProveedor::class,
        ]);
    }
}
