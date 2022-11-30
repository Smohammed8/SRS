<?php

namespace App\Form;

use App\Entity\Assessment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\HttpFoundation\File\File;


class AssessmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('note')
            ->add('note',CKEditorType::class)
            ->add('file', FileType::class, array(
                'attr' => array(
                    
                    'id' => 'filePhoto',
                  //  'class' => 'sr-only',
                    'data_class'=>null,
                    'accept' => 'image/jpeg,image/png,image/jpg,image/pdf'
                ),
                'label' => '',
                'required' =>false


            )) ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Assessment::class,
        ]);
    }
}
