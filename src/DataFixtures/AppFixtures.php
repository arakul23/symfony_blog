<?php

namespace App\DataFixtures;

use App\Entity\Blog;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $password = $this->hasher->hashPassword($user, 'pass_1234');
        $user->setPassword($password);
        $user->setEmail('admin@example.com');
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);


        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $password = $this->hasher->hashPassword($user, 'pass_1234');
            $user->setPassword($password);
            $user->setEmail('user' . $i . '@example.com');

            $manager->persist($user);

            for ($j = 0; $j < 100; $j++) {
                $blog = new Blog($user)->setTitle('title' . $j)->setDescription('description' . $j)->setText('text' . $j);

                $manager->persist($blog);
            }
        }

        $manager->flush();
    }
}
