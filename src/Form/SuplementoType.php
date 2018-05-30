<?php

namespace App\Form;

use App\Entity\Suplemento;
use App\Entity\Acuerdo;
use App\Entity\Contrato;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SuplementoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero')
            ->add('fechaInicio')
            ->add('fechaTerminacion')
            ->add('acuerdo',EntityType::class, array(
                'class' => Acuerdo::class,
                //'choice_label'=>'numero'
            ))
            ->add('contrato')
            ->add('contrato',EntityType::class, array(
                'class' => Contrato::class,
                'choice_label'=>'numero'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Suplemento::class,
        ]);
    }
}
