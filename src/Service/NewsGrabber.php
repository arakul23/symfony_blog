<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Blog;
use App\Repository\BlogRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DomCrawler\Crawler;

readonly class NewsGrabber
{
    private LoggerInterface $logger;

    public function __construct(
        private UserRepository         $userRepository,
        private EntityManagerInterface $entityManager,
        private BlogRepository         $blogRepository,
        private ParameterBagInterface  $parameterBag,
        private HttpClient             $httpClient
    )
    {
    }

    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    public function importNews(?int $count = null, ?bool $dryRun = false): void
    {
        $this->logger->info('Start getting news...');

        $news = [];

        $crawler = new Crawler($this->httpClient->get('https://engadget.com/news/'));

        $crawler->filter('h3 > a')->each(function (Crawler $node) use (&$news, $count) {
            if ($count && count($news) >= $count) {
                return;
            }

            $news[] = [
                'title' => $node->text(),
                'href' => $node->attr('href'),
            ];
        });

        $this->logger->info(sprintf('Got %d news', count($news)));


        foreach ($news as &$item) {
            $crawler = new Crawler($this->httpClient->get('https://engadget.com' . $item['href']));

            $text = $crawler->filter('div.columns-holder')->first();

            $item['text'] = $text->text();

            $this->logger->info(sprintf('Parsing news %s', $item['title']));
        }

        unset($item);

        $this->logger->info('Save news');

        $this->saveNews($news);
    }

    private function saveNews(array $texts, bool $dryRun = false): void
    {
        $blogUser = $this->userRepository->find($this->parameterBag->get('autoblog'));

        foreach ($texts as $item) {
            if ($this->blogRepository->getByTitle($item['title'])) {
                $this->logger->info(sprintf('News already exists %s', $item['title']));
                continue;
            }

            if ($dryRun) {
                continue;
            }

            $blog = new Blog($blogUser);
            $blog->setTitle($item['title']);
            $blog->setDescription(mb_substr($item['text'], 0, 200));
            $blog->setText($item['text']);
            $blog->setStatus('pending');

            $this->entityManager->persist($blog);
        }

        $this->entityManager->flush();
    }
}
