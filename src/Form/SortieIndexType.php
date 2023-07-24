<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Sortie;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SortieIndexType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            // Mise à disposition des filtres

            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'nom', // Affiche le nom du campus dans le menu déroulant
                'label' => 'Campus', // Label du champ
                'required' => false, // Rend le champ facultatif
                'placeholder' => 'Tous les campus', // Option par défaut affichée dans le menu déroulant
                'multiple' => false, // Permet de sélectionner un seul campus
                'expanded' => false, // Affiche un menu déroulant au lieu de cases à cocher
            ])
            ->add('nom', TextType::class, [ // Ajoutez le champ de texte pour la recherche par nom
                'label' => 'Nom de la sortie',
                'required' => false,
            ])
            ->add('dateHeureDebut', DateType::class, [
                'label' => 'Date de début',
                'required' => false,
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'datepicker'], // Optional, if you want to add a date picker widget
            ])
            ->add('organisateur', EntityType::class, [
                'class' => User::class,
                'label' => 'Sorties dont je suis organisateur/trice',
                'required' => false,

            ])
            ->add('caseACocherOrganisateur', CheckboxType::class, [
                'label' => 'Sorties dont je suis organisateur/trice',
                'required' => false,
                'mapped' => false, // This prevents the "organisateur" field from being mapped to the entity property
            ])
            ->add('bleu', CheckboxType::class, [
                'label' => 'Colorer le tableau en bleu',
                'required' => false,
                'mapped' => false
            ]);

        ;




    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
}
