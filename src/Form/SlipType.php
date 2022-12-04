<?php

namespace App\Form;

use App\Entity\Slip;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SlipType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
            ->add('department')
            ->add('course')
            ->add('semester')
            ->add('modality')
             ->add('year')
            ->add('priority')
         
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Slip::class,
        ]);
    }
}
