<?php

namespace App\Form;

use App\Entity\BedTransferHistory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BedTransferHistoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('transferredAt')
            ->add('admission')
            ->add('oldBed')
            ->add('newBed')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BedTransferHistory::class,
        ]);
    }
}
