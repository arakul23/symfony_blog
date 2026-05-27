<?php

declare(strict_types=1);

namespace App\Filter;

class BlogFilter
{
    private ?string $title = null;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }


}
