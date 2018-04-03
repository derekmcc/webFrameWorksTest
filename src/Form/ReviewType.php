<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('summary')
            ->add('retailers')
            ->add('price')
            ->add('stars', ChoiceType::class, array(
                'choices' => array(
                    '0' => 0,
                    '0.5' => 0.5,
                    '1' => 1,
                    '1.5' => 1.5,
                    '2' => 2,
                    '2.5' => 2.5,
                    '3' => 3,
                    '3.5' => 3.5,
                    '4' => 4,
                    '4.5' => 4.5,
                    '5' => 5,
                ),
            ))
           // ->add('image', FileType::class, array('required'=>false))
            //->add('image', FileType::class, array('data_class' => null))
           ->add('image', TextType::class, array(
               'label' => 'Image',
               'attr' => ['readonly' => true],
               'required'    => false
           ))
            ->add('image2', FileType::class, array(
                'label' => 'Image',
                'data_class' => null,
                'required'    => false
            ))
          /*  ->add('recipe',
               EntityType::class, [
                   // list objects from this class
                   'class' => 'App:Recipe',
                   'choice_label' => 'title',
               ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
