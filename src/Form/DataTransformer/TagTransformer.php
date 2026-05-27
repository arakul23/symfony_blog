<?php

// src/Form/DataTransformer/IssueToNumberTransformer.php
namespace App\Form\DataTransformer;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TagTransformer implements DataTransformerInterface
{
    public function __construct(
        readonly private EntityManagerInterface $entityManager,
        readonly private TagRepository $tagRepository
    ) {
    }

    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  Tag|null $tag
     */
    public function transform($value): string
    {
        if (null === $value) {
            return '';
        }

        $array = [];

        foreach ($value as $item) {
            $array[] = $item->getName();
        }

        return implode(',', $array);
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $value
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($value): ?ArrayCollection
    {
        $array = new ArrayCollection();

        if (!$value) {
            return $array;
        }

        // 1. Получаем массив уникальных имен из строки
        $itemNames = array_unique(explode(',', $value));

// 2. Достаем из базы только те теги, которые уже существуют
        $existingTags = $this->tagRepository->findBy(['name' => $itemNames]);

// 3. Создаем карту существующих имен, чтобы удобно находить их разницу
        $existingNames = array_map(fn($tag) => $tag->getName(), $existingTags);

        $existingTags = $this->tagRepository->findBy(['name' => $itemNames]);
        $existingNames = array_map(fn($tag) => $tag->getName(), $existingTags);
        $missingNames = array_diff($itemNames, $existingNames);

// 5. Превращаем новые имена в объекты Tag через array_map
        $newTags = array_map(fn($name) => (new Tag())->setName($name), $missingNames);

// 6. Объединяем существующие и новые теги в одну ArrayCollection
        return new ArrayCollection(array_merge($existingTags, $newTags));
    }
}
