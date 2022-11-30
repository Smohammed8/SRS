<?php

namespace App\Form;

use App\Entity\HealthFacility;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class HealthFacilityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
      

          ->add('ownership', ChoiceType::class, array(
                
            'choices'  => array(
              'Select  ownership...' =>'',
              'Public' =>'Public',
              'Private' =>'Private',
          ),
          'required'=>true,
      ))

      ->add('type', ChoiceType::class, array(
                
        'choices'  => array(
          'Select  type of health facility...' =>'',
          'Medical Center' =>'Medical Center',
          'Primary Hospital' =>'Primary Hospital',
          'Seconary Hospital' =>'Seconary Hospital',
          'Tertiary Hospital' =>'Tertiary Hospital',
          'General Hospital' =>'General Hospital',
          'Specilised Hospital' =>'Specilised Hospital',
          'Health Center' =>'Health Center',
          'Higher Clinic' =>'Higher Clinic',
          'Medium Clinic' =>'Medium Clinic',
          'Primary Clinic' =>'Primary Clinic',
          'Others' =>'Others',
      ),
      'required'=>true,
  ))

            ->add('woreda')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HealthFacility::class,
        ]);
    }
}
