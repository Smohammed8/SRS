<?php

namespace App\Form;
use App\Entity\User;
use App\Entity\Ward;
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
            "Gate Keeper"=>"ROLE_GATE_KEEPER",
            'Checkpoint' => 'ROLE_CHECKPOINT',
            'Liaison Officer' => 'ROLE_LIAISON',
            'Physician' => 'ROLE_PHYSICIAN',
            'Nurse' => 'ROLE_NURSE',
            'Card worker' => 'ROLE_DATA_ENCODER'
           
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
           

               ->add('ward', EntityType::class, [
            'class' =>Ward::class,
            'required'=>false,
            'query_builder' => function (EntityRepository $err) {
                $result = $err->createQueryBuilder('e')
             
                       ->orderBy('e.name', 'ASC');   

                 
                return $result;
            },
        
            'placeholder' => "All wards...",
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
