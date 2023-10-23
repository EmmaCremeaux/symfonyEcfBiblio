<?php

namespace App\Form;

use App\Entity\Emprunt;
use App\Entity\Emprunteur;
use App\Entity\Livre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmpruntType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateEmprunt')
            ->add('dateRetour')
            ->add('livre', EntityType::class, [
                'class' => Livre::class,
                'choice_label' => function(Livre $livre) {
                    return "{$livre->getTitre()} (id {$livre->getId()})";
                },
                'multiple' => false,
                'expanded' => true,
                'attr' => [
                    'class' => 'form_scrollable-checkboxes',
                ],
            ]) 
            
            ->add('emprunteur', EntityType::class, [
                'class' => Emprunteur::class,
                'choice_label' => function(Emprunteur $emprunteur) {
                    return "{$emprunteur->getNom()} (id {$emprunteur->getId()})";
                },
                'multiple' => false,
                'expanded' => true,
                'attr' => [
                    'class' => 'form_scrollable-checkboxes',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Emprunt::class,
        ]);
    }
}
