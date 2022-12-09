<?php

namespace App\Form;

use App\Entity\Course;
//use App\Entity\CourseType;
use App\Entity\Department;
use App\Entity\Program;
use App\Entity\Semester;
use App\Entity\Year;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('ccode')
            ->add('creditHour')
            ->add('ects')
          
            ->add('program', EntityType::class, [
                'class' =>Program::class,
                'query_builder' => function (EntityRepository $err) {
                    $result = $err->createQueryBuilder('e')
                 
                           ->orderBy('e.name', 'ASC');   
        
                     
                    return $result;
                },
            
                'placeholder' => "Select program...",
            ])

            ->add('year', EntityType::class, [
                'class' =>Year::class,
                'query_builder' => function (EntityRepository $err) {
                    $result = $err->createQueryBuilder('e')
                 
                           ->orderBy('e.name', 'ASC');   
        
                     
                    return $result;
                },
            
                'placeholder' => "Select year...",
            ])

            ->add('semester', EntityType::class, [
                'class' =>Semester::class,
                'query_builder' => function (EntityRepository $err) {
                    $result = $err->createQueryBuilder('e')
                 
                           ->orderBy('e.name', 'ASC');   
        
                     
                    return $result;
                },
            
                'placeholder' => "Select semester...",
            ])


            // ->add('type', EntityType::class, [
            //     'class' =>CourseType::class,
            //     'query_builder' => function (EntityRepository $err) {
            //         $result = $err->createQueryBuilder('e')
                 
            //                ->orderBy('e.name', 'ASC');   
        
                     
            //         return $result;
            //     },
            
            //     'placeholder' => "Select course type...",
            // ])
            ->add('prerequisite')

            ->add('type')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
