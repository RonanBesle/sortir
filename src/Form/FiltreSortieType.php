<?php

namespace App\Form;

use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltreSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'nom', // Affiche le nom du campus dans le menu déroulant
                'label' => 'Campus', // Label du champ
                'required' => false, // Rend le champ facultatif
                'placeholder' => 'Tous les campus', // Option par défaut affichée dans le menu déroulant
                'multiple' => false, // Permet de sélectionner un seul campus
                'expanded' => false, // Affiche un menu déroulant au lieu de cases à cocher
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'campus_choices' => [],
        ]);
    }
}
