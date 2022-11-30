<?php

namespace App\Form;

use App\Entity\Visitor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class VisitorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('sex')


            ->add('sex', ChoiceType::class, array(
                
                'choices'  => array(
                  'Select Sex...' =>'',
                  'Male' =>'M',
                  'Female' =>'F',
              ),
              'required'=>true,
          ))
          ->add('relation', ChoiceType::class, array(
                
            'choices'  => array(
              'Select relationship...' =>'',
              'Friend' =>'Friend',
              'Relative' =>'Relative',
              'Family' =>'Family',
              'Neighboring' =>'Neighboring',
              'Co-worker' =>'Coworker',
              'Goodwill' =>'Goodwill',
          ),
          'required'=>true,
      ))
           // ->add('dateOfVisit')
           // ->add('user')
           // ->add('patient')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Visitor::class,
        ]);
    }
}
