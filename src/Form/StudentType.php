<?php

namespace App\Form;

use App\Entity\Modality;
use App\Entity\Nationality;

use App\Entity\Student;
use App\Entity\Woreda;
use App\Entity\Program;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TelType;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('studentId')

            ->add('firstName')
            ->add('fatherName')
            ->add('lastName')
            ->add('gender', ChoiceType::class, array(  
                'choices'  => array(
                'Select Sex..' =>'',
                'Male' =>'M',
                'Female' =>'F'),
                 'required'=>true,
                // 'mapped'=>false,
                 'attr'=> array(
                  'class'=>'form-control select2'
    
                 )
        ))
           

            ->add('woreda', EntityType::class, [
                'class' =>Woreda::class,
                'query_builder' => function (EntityRepository $err) {
                    $result = $err->createQueryBuilder('e')
                 
                           ->orderBy('e.name', 'ASC');   
        
                     
                    return $result;
                },
            
                'placeholder' => "Select woreda...",
            ])


            ->add('program', EntityType::class, [
                'class' =>Program::class,
                'query_builder' => function (EntityRepository $err) {
                    $result = $err->createQueryBuilder('e')
                 
                           ->orderBy('e.name', 'ASC');   
        
                     
                    return $result;
                },
            
                'placeholder' => "Select region...",
            ])


            // ->add('programLevel', EntityType::class, [
            //     'class' =>ProgramLevel::class,
            //     'query_builder' => function (EntityRepository $err) {
            //         $result = $err->createQueryBuilder('e')
                 
            //                ->orderBy('e.name', 'ASC');   
        
                     
            //         return $result;
            //     },
            
            //     'placeholder' => "Select program level...",
            // ])


           

            ->add('modality', EntityType::class, [
                'class' =>Modality::class,
                'query_builder' => function (EntityRepository $err) {
                    $result = $err->createQueryBuilder('e')
                 
                           ->orderBy('e.name', 'ASC');   
        
                     
                    return $result;
                },
            
                'placeholder' => "Select modality...",
            ])


            ->add('nationality', EntityType::class, [
                'class' =>Nationality::class,
                'query_builder' => function (EntityRepository $err) {
                    $result = $err->createQueryBuilder('e')
                 
                           ->orderBy('e.name', 'ASC');   
        
                     
                    return $result;
                },
            
                'placeholder' => "Select nationality...",
            ])


         
            ->add('phone')

            ->add('academicYear', ChoiceType::class, array(  
                'choices'  => array(
               'Select admission year..' =>'',
              '2015' =>2015,
              '2016' =>2016,
              '2017' =>2019,
              '2018' =>2018,
              '2019' =>2019,
              '2020' =>2020,
              '2021' =>2021
            
            
            ),
                 'required'=>true,
                 //'mapped'=>false,
                 'attr'=> array(
                  'class'=>'form-control select2'
    
                 )
        ))
        



 

/*
        ->add('dob',DateType::class,[
            'widget'=>'single_text',
           
     ]) */
        
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
