<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class)
            ->add('summary',TextType::class)
            ->add('description',TextType::class)
            ->add('image', FileType::class, array('data_class' => null))
            ->add('ingredients',TextType::class)
            ->add('price', ChoiceType::class, array(
                'choices' => array(
                    'Under €10' => 'Under €10',
                    '€11-20' => '€11-20',
                    '€25-30'   => '€25-30',
                    '€31-40'   => '€31-40',
                    'Over €40' => 'Over €40',
                    ),
            ))
          //  ->add('reviews')
           // ->add('isPublic')
           // ->add('save', SubmitType::class, array(
           //     'attr' => array('class' => 'save'),
           // ));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
