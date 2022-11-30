<?php

namespace App\Form;

use App\Entity\Admimssion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdmimssionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('createdAt')
            // ->add('dischargedAt')
          //  ->add('duration')
           // ->add('outcome')
           // ->add('user')
            ->add('patient')
             ->add('bed')

             ->add('type')
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Admimssion::class,
        ]);
    }
}
