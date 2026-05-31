<?php

namespace App\Form;

use App\Entity\Blog;
use App\Entity\Category;
use App\Entity\User;
use App\Form\DataTransformer\TagTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogType extends AbstractType
{
    public function __construct(private TagTransformer $tagTransformer, private Security $security)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
            ])
            ->add('description', TextareaType::class)
            ->add('text')

            ->add('tags', TextType::class, [
                'required' => false,
            ])
        ;

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $builder->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Select a category',
            ])
                ->add('user', EntityType::class, [
                    'class' => User::class,
                    'choice_label' => 'email',
                    'placeholder' => 'Select user',
                ]);
        }

        $builder->get('tags')
            ->addModelTransformer($this->tagTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
        ]);
    }
}
