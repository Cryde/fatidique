<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExploreController extends AbstractController
{
    private const ITEMS_PER_PAGE = 12;

    #[Route('/explore', name: 'explore', priority: 10)]
    public function index(Request $request, EventRepository $eventRepository): Response
    {
        $query = $request->query->get('q', '');
        $theme = $request->query->get('theme', 'all');
        $period = $request->query->get('period', 'future');
        $page = max(1, (int) $request->query->get('page', 1));

        $events = $eventRepository->searchPublicEvents(
            $query ?: null,
            $theme,
            $period,
            $page,
            self::ITEMS_PER_PAGE
        );

        $totalResults = $eventRepository->countSearchResults(
            $query ?: null,
            $theme,
            $period
        );

        $totalPages = (int) ceil($totalResults / self::ITEMS_PER_PAGE);

        return $this->render('explore/index.html.twig', [
            'events' => $events,
            'query' => $query,
            'theme' => $theme,
            'period' => $period,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalResults' => $totalResults,
            'themes' => Event::THEMES,
        ]);
    }
}
