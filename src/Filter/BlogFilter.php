<?php

declare(strict_types=1);

namespace App\Filter;

use App\Entity\User;

class BlogFilter
{
    private ?string $title = null;

    public function __construct(private ?User $user = null)
    {
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }


}
