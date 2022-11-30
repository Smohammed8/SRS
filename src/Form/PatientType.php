<?php

namespace App\Form;

use App\Entity\Patient;
use App\Entity\Indigent;
use App\Entity\Agreement;
use App\Entity\Woreda;
use App\Entity\Zone;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

use Doctrine\ORM\EntityRepository;
use Proxies\__CG__\App\Entity\Region;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
class PatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('MRN')
            ->add('fname')
            ->add('mname')

            ->add('lname',null,[

             'required' =>false
            ])
      
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


               ->add('phone',TelType::class,[

                'required' =>false

               ])


               ->add('address')

               ->add('referredFrom')
         
        ->add('region', EntityType::class, [
            'class' =>Region::class,
            'query_builder' => function (EntityRepository $err) {
                $result = $err->createQueryBuilder('e')
             
                       ->orderBy('e.name', 'ASC');   

                 
                return $result;
            },
        
            'placeholder' => "Select region...",
        ])
               ->add('zone', EntityType::class, [
                'class' => Zone::class,
                'query_builder' => function (EntityRepository $err) {
                    $ress = $err->createQueryBuilder('u')
                 
                           ->orderBy('u.name', 'ASC');   

                     
                    return $ress;
                },
            
                'placeholder' => "Select zone...",
            ])

            ->add('woreda', EntityType::class, [
                'class' => Woreda::class,
                'query_builder' => function (EntityRepository $err) {
                    $ress = $err->createQueryBuilder('u')
                 
                           ->orderBy('u.name', 'ASC');   

                     
                    return $ress;
                },
            
                'placeholder' => "Select woreda...",
            ])

  /*
            ->add('dob',DateType::class,[
                'widget'=>'single_text',
               
         ]) */
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
        ]);
    }
}
