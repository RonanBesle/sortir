<?php

namespace App\Form;

use App\DTO\SortieIndexFiltreDTO;
use App\Entity\Campus;
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

            ->add('nomRecherche', TextType::class, [ // Ajoutez le champ de texte pour la recherche par nom
                'label' => 'Nom de la sortie',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])

              ->add('campusRecherche', EntityType::class, [
                  'class' => Campus::class,
                  'label' => 'Campus',
                  'required' => false,
              ])

          ->add('organisateurBoolean', CheckboxType::class, [
              'label' => 'Sortie dont je suis organisateur/trice',
              'required' => false,
          ])
        ;

    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SortieIndexFiltreDTO::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return ''; // TODO: Change the autogenerated stub
    }
}
