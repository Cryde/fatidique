<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AgendaController extends AbstractController
{
    #[Route('/agenda', name: 'agenda', priority: 10)]
    public function index(Request $request, EventRepository $eventRepository): Response
    {
        $monthParam = $request->query->get('month');

        if ($monthParam && preg_match('/^(\d{4})-(\d{2})$/', $monthParam, $matches)) {
            $year = (int) $matches[1];
            $month = (int) $matches[2];
        } else {
            $year = (int) date('Y');
            $month = (int) date('m');
        }

        $events = $eventRepository->findPublicEventsByMonth($year, $month);

        // Group events by day
        $eventsByDay = [];
        foreach ($events as $event) {
            $day = (int) $event->getDate()->format('j');
            if (!isset($eventsByDay[$day])) {
                $eventsByDay[$day] = [];
            }
            $eventsByDay[$day][] = $event;
        }

        // Build calendar grid
        $firstDay = new \DateTime("$year-$month-01");
        $lastDay = (clone $firstDay)->modify('last day of this month');
        $daysInMonth = (int) $lastDay->format('j');

        // Get day of week for first day (1=Monday, 7=Sunday)
        $startDayOfWeek = (int) $firstDay->format('N');

        // Previous/next month
        $prevMonth = (clone $firstDay)->modify('-1 month');
        $nextMonth = (clone $firstDay)->modify('+1 month');

        return $this->render('agenda/index.html.twig', [
            'year' => $year,
            'month' => $month,
            'monthName' => $this->getMonthName($month),
            'daysInMonth' => $daysInMonth,
            'startDayOfWeek' => $startDayOfWeek,
            'eventsByDay' => $eventsByDay,
            'prevMonth' => $prevMonth->format('Y-m'),
            'nextMonth' => $nextMonth->format('Y-m'),
            'themes' => Event::THEMES,
        ]);
    }

    private function getMonthName(int $month): string
    {
        $months = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ];
        return $months[$month] ?? '';
    }
}
