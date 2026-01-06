<?php

namespace App\Command;

use App\Repository\EventRepository;
use App\Service\LikeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:backfill-event-author',
    description: 'Backfill author for events without one (based on title hash)',
)]
class BackfillEventAuthorCommand extends Command
{
    public function __construct(
        private readonly EventRepository $eventRepository,
        private readonly LikeService $likeService,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $events = $this->eventRepository->findBy(['author' => null]);

        if (count($events) === 0) {
            $io->success('No events to update.');
            return Command::SUCCESS;
        }

        $io->info(sprintf('Updating %d events...', count($events)));

        foreach ($events as $event) {
            $author = $this->likeService->generateUsernameFromString($event->getLabel());
            $event->setAuthor($author);
        }

        $this->entityManager->flush();

        $io->success(sprintf('Updated %d events with authors.', count($events)));

        return Command::SUCCESS;
    }
}
