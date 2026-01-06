<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\Type\EventType;
use App\Repository\EventRepository;
use App\Service\EventSlug;
use App\Service\LikeService;
use App\Service\SlugRandomize;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EventController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/', name: "homepage")]
    public function indexAction(EventRepository $eventRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'lastEvents'        => $eventRepository->findLastPublicEvents(),
            'almostEndedEvents' => $eventRepository->findAlmostEndedPublicEvents(),
            'mostLikedEvents'   => $eventRepository->findMostLikedPublicEvents(),
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/create', name: 'event_create')]
    public function create(SlugRandomize $slugRandomize, Request $request, EventSlug $eventSlug): Response
    {
        $event = new Event();
        $form  = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->entityManager;
            $event->setSlug($eventSlug->create($event->getLabel()));
            $slugRandomize->randomizeSlug($event);
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('event_view', ['slug' => $event->getSlug()]);
        }

        return $this->render('event/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/{slug:event}', name: 'event_view')]
    public function show(Event $event, LikeService $likeService, Request $request): Response
    {
        $ip = $request->getClientIp() ?? '127.0.0.1';

        return $this->render('event/view.html.twig', [
            'event' => $event,
            'hasLiked' => $likeService->hasLiked($event, $ip),
        ]);
    }

    #[Route('/{slug:event}/like', name: 'event_like', methods: ['POST'])]
    public function like(Event $event, LikeService $likeService, Request $request): JsonResponse
    {
        $ip = $request->getClientIp() ?? '127.0.0.1';
        $result = $likeService->toggleLike($event, $ip);

        return new JsonResponse($result);
    }
}
