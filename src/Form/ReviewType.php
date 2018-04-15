<?php
/**
 * Review form for adding/editing review items.
 */

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Start of the review type class
 * Class ReviewType
 * @package App\Form
 */
class ReviewType extends AbstractType
{
    /**
     * Builds the review form for editing/adding review items
     * @param FormBuilderInterface $builder
     * @param array $options
     */
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
           ->add('image', FileType::class, [
               'label' => 'Image',
               'data_class' => null,
               'required' => false
           ])
        ;
    }

    /**
     * Configures the options for this review type
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
