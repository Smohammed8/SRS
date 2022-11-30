<?php

namespace App\Form;

use App\Entity\VitalSign;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VitalSignType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('temperature')
            ->add('heartRate')
            ->add('respiratoryRate')
            ->add('bloodPressure')
            ->add('encounter')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => VitalSign::class,
        ]);
    }
}
