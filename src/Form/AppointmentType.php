<?php

namespace App\Form;

use App\Entity\Appointment;
use App\Entity\Unit;
use App\Entity\Priority;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
            ->add('reason')
      
            ->add('unit', EntityType::class, [
                'class' => Unit::class,
                'required' => true,
                'query_builder' => function (EntityRepository $er) {
                    $res = $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                    return $res;
                },

                'placeholder' => "",
            ])
            ->add('priorityLevel', EntityType::class, [
                'class' => Priority::class,
                'required' => true,
                'query_builder' => function (EntityRepository $er) {
                    $res = $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                    return $res;
                },

                'placeholder' => "",
            ])

           ->add('whyAppointed')
             ->add('shift')

             ->add('shift', ChoiceType::class,["choices" => ["Select shift from Monday to Friday"=>null,"Morning [2:00-6:00]" => "Morning","Afternoon[7:30 -11:30] "=>"Afternoon"]])

           // ->add('patient')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
