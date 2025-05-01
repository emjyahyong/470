<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\DetailsCommande;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DetailsCommandeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantite')
            ->add('prix_unitaire')
            ->add('tva')
            ->add('total_ligne')
            ->add('commande', EntityType::class, [
                'class' => Commande::class,
                'choice_label' => 'id',
            ])
            ->add('produit', EntityType::class, [
                'class' => Produit::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DetailsCommande::class,
        ]);
    }
}
