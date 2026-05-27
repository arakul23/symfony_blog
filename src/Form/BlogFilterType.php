<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Blog;
use App\Entity\Category;
use App\Filter\BlogFilter;
use App\Form\DataTransformer\TagTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogFilterType extends AbstractType
{
    public function __construct(private TagTransformer $tagTransformer)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('GET')
            ->add('title', TextType::class, [
                'required' => true,
            ])
            ->add('content', TextType::class, [
                'mapped' => false,
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlogFilter::class,
        ]);
    }
}
