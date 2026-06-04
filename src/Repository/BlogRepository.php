<?php

namespace App\Repository;

use App\Entity\Blog;
use App\Entity\User;
use App\Filter\BlogFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Blog>
 */
class BlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blog::class);
    }

    public function getBlogs(): array
    {
        return $this->createQueryBuilder('b')->getQuery()->setMaxResults(6)->getResult();
    }

    public function findByBlogFilter(BlogFilter $filter)
    {
        $query = $this->createQueryBuilder('b')->leftJoin(User::class, 'u', 'WITH', 'u.id=b.user');

        if ($filter->getUser()) {
            $query->andWhere('b.user = :user')
                ->setParameter('user', $filter->getUser());
        }

        if ($filter->getTitle()) {
            $query->andWhere('b.title LIKE :title')
                ->setParameter('title', '%' . $filter->getTitle() . '%');
        }

        return $query;
    }
}
