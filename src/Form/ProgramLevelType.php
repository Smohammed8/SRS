<?php

namespace App\Form;

use App\Entity\ProgramLevel;
use App\Entity\Program;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TelType;


class ProgramLevelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            
            ->add('program', EntityType::class, [
                'class' =>Program::class,
                'query_builder' => function (EntityRepository $err) {
                    $result = $err->createQueryBuilder('e')
                 
                           ->orderBy('e.name', 'ASC');   
        
                     
                    return $result;
                },
            
                'placeholder' => "Select region...",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProgramLevel::class,
        ]);
    }
}
