<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Blog;
use App\Message\ContentWatchJob;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsDoctrineListener(event: Events::postFlush)]
#[AsDoctrineListener(event: Events::postPersist)]

class BlogListener
{

    private array $entities = [];

    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function postFlush(PostFlushEventArgs $event): void
    {
      foreach ($this->entities as $entity) {
          $this->messageBus->dispatch(new ContentWatchJob($entity->getId()));
      }
    }

    public function postPersist(PostPersistEventArgs $event): void
    {
        if ($event->getObject() instanceof Blog) {
            $this->entities[] = $event->getObject();
        }
    }
}
