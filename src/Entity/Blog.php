<?php

namespace App\Entity;

use App\Repository\BlogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: BlogRepository::class)]
class Blog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[NotBlank]
    private ?string $title = null;

    #[ORM\Column(length: 255, options: ['default' => 'text'])]
    private ?string $description = 'text';

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ManyToOne(targetEntity: Category::class)]
    #[JoinColumn(name: 'category_id', referencedColumnName: 'id')]
    private Category|null $category = null;

    #[ManyToOne(targetEntity: User::class, fetch: 'EAGER')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private ?User $user = null;

    #[JoinTable(name: 'blog_tags')]
    #[JoinColumn(name: 'blog_id', referencedColumnName: 'id')]
    #[InverseJoinColumn(name: 'tag_id', referencedColumnName: 'id')]
    #[ManyToMany(targetEntity: Tag::class, cascade: ['persist'])]
    private ArrayCollection|PersistentCollection $tags;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $percent = null;


    public function __construct(UserInterface|User $user)
    {
        $this->user = $user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }

    public function getTags(): ArrayCollection|PersistentCollection
    {
        return $this->tags;
    }

    public function setTags(ArrayCollection $tags): void
    {
        $this->tags = $tags;
    }

    public function addTag(Tag $tag): void
    {
        $this->tags[] = $tag;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getPercent(): ?string
    {
        return $this->percent;
    }

    public function setPercent(?int $percent): void
    {
        $this->percent = $percent;
    }
}
