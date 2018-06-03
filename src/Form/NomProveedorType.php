<?php

namespace App\Form;

use App\Entity\NomProveedor;
use App\Entity\NomProvincia;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NomProveedorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder           
            ->add('nombre')
            ->add('provincia', ChoiceType::class, array(
                'choices'  => $options["provincias_parsed"]
            ))
            ->add('direccion',TextareaType::class, array(
                'attr' => array('class' => 'form-control'),
            ))
            ->add('telefono')
            ->add('correo')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NomProveedor::class,
            'provincias_parsed' => null,
        ]);
    }
}
