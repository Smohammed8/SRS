<?php

namespace App\Form;

use App\Entity\Bed;
use App\Entity\Ward;
use App\Entity\Unit;
use App\Entity\Room;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')

            //->add('room')
   

            ->add('isFunctional', ChoiceType::class, array(
                
                'choices'  => array(
                  //'Select bed functional Status...' =>'',
                  'Yes' =>'Yes',
                  'No' =>'No',
              ),
              'required'=>true,
          ))

    //       ->add('hasOxgen', ChoiceType::class, array(   
    //         'choices'  => array(
    //           'Has the bed oxgen?' =>'',
    //           'No' =>'No',
    //           'Yes' =>'Yes',
            
    //       ),
    //       'required'=>true,
    //   ))
     

            ->add('type', ChoiceType::class, array(
                
                'choices'  => array(
              //    'Select bed type...' =>'',
                  'Single' =>'Single',
                  'Double' =>'Double',
              ),
              'required'=>true,
          ))




            // ->add('ward', EntityType::class, [
            //     'class' => Ward::class,
            //     'required'=>true,
            //     'query_builder' => function (EntityRepository $er) {
            //         $res = $er->createQueryBuilder('u')
            //         ->orderBy('u.name', 'ASC');
            //         return $res;
            //     },
            
            //     'placeholder' => "Select  ward...",
            // ])
            // ->add('unit', EntityType::class, [
            //     'class' => Unit::class,
            //     'required'=>true,
            //     'query_builder' => function (EntityRepository $er) {
            //         $res = $er->createQueryBuilder('u')
            //         ->orderBy('u.name', 'ASC');
            //         return $res;
            //     },
            
            //     'placeholder' => "Select  unit...",
            // ])

         
            // ->add('room', EntityType::class, [
            //     'class' => Room::class,
            //     'required'=>true,
            //     'query_builder' => function (EntityRepository $er) {
            //         $res = $er->createQueryBuilder('u')
            //         ->orderBy('u.name', 'ASC');
            //         return $res;
            //     },
            
            //     'placeholder' => "Select  room...",
            // ])
            // ->add('accessibility')
            // ->add('currentStatus')
           // ->add('room')
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bed::class,
        ]);
    }
}
