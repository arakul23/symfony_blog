<?php

namespace App\Tests\Kernel\App\Repository;

use App\Factory\BlogFactory;
use App\Factory\UserFactory;
use App\Repository\BlogRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class BlogRepositoryTest extends KernelTestCase
{
    use Factories, ResetDatabase;

    public function testSomething(): void
    {
        self::bootKernel();

        $user = UserFactory::createOne();

        BlogFactory::createMany(7, ['user' => $user]);

        $blogRepository = static::getContainer()->get(BlogRepository::class);

        $blogs = $blogRepository->getBlogs();

        $this->assertCount(6, $blogs);
        $this->assertSame('blogTitle', $blogs[0]->getTitle());
    }
}
