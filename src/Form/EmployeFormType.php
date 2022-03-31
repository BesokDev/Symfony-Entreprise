<?php

namespace App\Form;

use App\Entity\Employe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class EmployeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prénom',
                ],
            ])
            ->add('nom', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom',
                ],
            ])
            ->add('telephone', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Téléphone',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Email',
                ],
            ])
            ->add('adresse', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Adresse',
                ],
            ])
            ->add('poste', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Sélectionnez un service' => null,
                    'Direction' => 'direction',
                    'Commercial' => 'commercial',
                    'Marketing' => 'marketing',
                    'Informatique' => 'informatique',
                ],
            ])
            ->add('salaire', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Salaire',
                ],
            ])
            ->add('dateDeNaissance', DateType::class, [
                'label' => false,
                'widget' => 'single_text',
                'help' => '<i>Date de naissance</i>',
                'help_html' => true,
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
            'allow_file_upload' => true,
        ]);
    }
}
