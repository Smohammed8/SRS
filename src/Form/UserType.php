<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UserType extends AbstractType
{
   public function buildForm(FormBuilderInterface $builder, array $options)
    {
             $role=[
      
                "System Adminstrator"=>"ROLE_ADMIN",
                "Student"=>"ROLE_STUDENT",
                'Instructure' => 'ROLE_INSTRUCTURE',
                'Registrar Officer' => 'ROLE_REGISTRAR_OFFICER',
                'Data encoder' => 'ROLE_DATA_ENCODER'
           
        ];

        $builder
        
        ->add('roles', ChoiceType::class,[
            "choices" =>$role,
            'mapped'=>false,
            'multiple'=>true,
            "placeholder"=>"Select Role"])
            ->add('firstName')
            ->add('fatherName')
            ->add('lastName')


            ->add('sex', ChoiceType::class,["choices" => ["Select sex"=>null,"Male" => "M","Female"=>"F"]])
            
            //->add('email',EmailType::class,['mapped'=>false]) 
            ->add('email') 
            ->add('phone')
           

               ->add('campus', EntityType::class, [
            'class' =>Campus::class,
            'required'=>false,
            'query_builder' => function (EntityRepository $err) {
                $result = $err->createQueryBuilder('e')
             
                       ->orderBy('e.name', 'ASC');   

                 
                return $result;
            },
        
            'placeholder' => "Head office...",
        ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
