<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('author')
        ->add('date')
        ->add('summary')
        ->add('retailers')
        ->add('price')
        ->add('stars')
        ->add('image')
        ->add('recipes',
            EntityType::class, [
                // list objects from this class
                'class' => 'App:Recipe',
                // use the 'Category.name' property as the visible option string
                'choice_label' => 'title',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
