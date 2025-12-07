<?php

namespace App\Form;

use App\Entity\Dossier;
use App\Entity\Images;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class DossierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('description', TextType::class, [
                'label' => 'Description'
            ])
            ->add('file', FileType::class, [
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
            'data_class' => Dossier::class,
        ]);
    }
}
