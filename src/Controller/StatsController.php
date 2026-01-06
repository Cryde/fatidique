<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\CommentRepository;
use App\Repository\EventLikeRepository;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StatsController extends AbstractController
{
    #[Route('/stats', name: 'stats', priority: 10)]
    public function index(
        EventRepository $eventRepository,
        EventLikeRepository $eventLikeRepository,
        CommentRepository $commentRepository
    ): Response {
        $stats = [
            'totalEvents' => $eventRepository->countAll(),
            'totalLikes' => $eventLikeRepository->countAll(),
            'totalComments' => $commentRepository->countAll(),
            'eventsEndingToday' => $eventRepository->countEndingToday(),
            'mostPopularTheme' => $eventRepository->findMostPopularTheme(),
            'themeDistribution' => $eventRepository->getThemeDistribution(),
            'averageCountdownDays' => $eventRepository->getAverageCountdownDays(),
        ];

        return $this->render('stats/index.html.twig', [
            'stats' => $stats,
            'themes' => Event::THEMES,
        ]);
    }
}
