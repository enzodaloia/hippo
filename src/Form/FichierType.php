<?php

namespace App\Form;

use App\Entity\Dossier;
use App\Entity\Fichier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class FichierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ext', FileType::class, [
                'label' => 'Joindre un fichier',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        /*'maxSize' => '50k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],*/
                        'mimeTypesMessage' => 'Please upload a valid document',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fichier::class,
        ]);
    }
}
