<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\ContentWatchJob;
use App\Repository\BlogRepository;
use App\Service\ContentWatchApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class ContentWatchHandler
{
    public function __construct(private ContentWatchApi $contentWatchApi, private EntityManagerInterface $entityManager, private BlogRepository $blogRepository)
    {
    }

    public function __invoke(ContentWatchJob $contentWatchJob)
    {
        $blogId = (int)$contentWatchJob->getContent();
        $blog = $this->blogRepository->find($blogId);
        $blog->setPercent($this->contentWatchApi->checkText($blog->getText()));

        $this->entityManager->flush();
    }
}
