<?php

namespace App\Form;

use App\Entity\Semester;
use App\Entity\Slip;
use App\Entity\Year;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;


class SlipType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
    
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
        
            'placeholder' => "Select semeseter...",
        ])

         
         
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Slip::class,
        ]);
    }
}
