<?php
/**
 * Recipe form for adding/editing recipe items.
 */

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\Review;
use App\Entity\User;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Start of the recipe type class
 * Class RecipeType
 * @package App\Form
 */
class RecipeType extends AbstractType
{
    /**
     * Builds the recipe form for editing/adding recipe items
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('summary')
            ->add('description')
            ->add('image', FileType::class, [
                'label' => 'Image',
                'data_class' => null,
                'required' => false
            ])
            ->add('ingredients')
            ->add('price', ChoiceType::class, array(
                'choices' => array(
                    'Under €10' => 'Under €10',
                    '€11-20' => '€11-20',
                    '€21-30'   => '€21-30',
                    '€31-40'   => '€31-40',
                    'Over €40' => 'Over €40',
                    ),
            ))
            ->add('requestRecipePublic', CheckboxType::class, array(
                'label'    => 'Request this dink br made public?',
                'required' => false,
            ))
        ;
    }

    /**
     * Configures the options for this recipe type
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
