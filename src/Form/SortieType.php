<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateHeureDebut')
            ->add('duree')
            ->add('dateLimiteInscription')
            ->add('nbInscriptionsMax')
            ->add('infosSortie')

            ->add('etat')
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'nom', // Specify the property to use as the string representation
                'label' => 'Campus',
                'required' => false,
                'placeholder' => 'Tous les campus',
                'multiple' => false,
                'expanded' => false,
            ])
            /*->add('lieu')*/
            ->add('lieu', LieuNewSortieType::class)
            ->add('users')
            ->add('organisateur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
