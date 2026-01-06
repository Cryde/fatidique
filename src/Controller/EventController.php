<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Event;
use App\Form\Type\EventType;
use App\Repository\CommentRepository;
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
    public function create(SlugRandomize $slugRandomize, Request $request, EventSlug $eventSlug, LikeService $likeService): Response
    {
        $event = new Event();
        $form  = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->entityManager;
            $event->setSlug($eventSlug->create($event->getLabel()));
            $slugRandomize->randomizeSlug($event);

            // Set author based on IP
            $ip = $request->getClientIp() ?? '127.0.0.1';
            $event->setAuthor($likeService->generateUsername($ip));

            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('event_view', ['slug' => $event->getSlug()]);
        }

        return $this->render('event/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/{slug:event}', name: 'event_view')]
    public function show(Event $event, LikeService $likeService, CommentRepository $commentRepository, Request $request): Response
    {
        $ip = $request->getClientIp() ?? '127.0.0.1';

        return $this->render('event/view.html.twig', [
            'event' => $event,
            'hasLiked' => $likeService->hasLiked($event, $ip),
            'comments' => $commentRepository->findByEvent($event),
        ]);
    }

    #[Route('/{slug:event}/like', name: 'event_like', methods: ['POST'])]
    public function like(Event $event, LikeService $likeService, Request $request): JsonResponse
    {
        $ip = $request->getClientIp() ?? '127.0.0.1';
        $result = $likeService->toggleLike($event, $ip);

        return new JsonResponse($result);
    }

    #[Route('/{slug:event}/comment', name: 'event_comment', methods: ['POST'])]
    public function comment(Event $event, LikeService $likeService, CommentRepository $commentRepository, Request $request): Response
    {
        $ip = $request->getClientIp() ?? '127.0.0.1';
        $ipHash = $likeService->hashIp($ip);

        // Check spam limit (5 comments per day per IP)
        if ($commentRepository->countByIpHashToday($ipHash) >= 5) {
            $this->addFlash('error', 'Vous avez atteint la limite de commentaires pour aujourd\'hui.');
            return $this->redirectToRoute('event_view', ['slug' => $event->getSlug()]);
        }

        $content = trim($request->request->get('content', ''));

        if (empty($content) || strlen($content) > 500) {
            $this->addFlash('error', 'Le commentaire doit contenir entre 1 et 500 caractères.');
            return $this->redirectToRoute('event_view', ['slug' => $event->getSlug()]);
        }

        $username = $likeService->generateUsername($ip);

        $comment = new Comment();
        $comment->setEvent($event);
        $comment->setAuthor($username);
        $comment->setContent($content);
        $comment->setIpHash($ipHash);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        $this->addFlash('success', 'Commentaire ajouté !');
        return $this->redirectToRoute('event_view', ['slug' => $event->getSlug()]);
    }
}
