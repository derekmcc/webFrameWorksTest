<?php

namespace App\Form;

use App\Entity\Recipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('summary')
            ->add('description')
            ->add('image')
            ->add('ingredients')
            ->add('price')
            ->add('category', EntityType::class, [
                // list objects from this class
                'class' => 'App:Category',
                // use the 'Category.name' property as the visible option string
                'choice_label' => 'name',
            ])
            ->add('review', EntityType::class, [
                // list objects from this class
                'class' => 'App:review',
                // use the 'Category.name' property as the visible option string
                'choice_label' => 'summary',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
