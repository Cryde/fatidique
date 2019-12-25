<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\Type\EventType;
use App\Repository\EventRepository;
use App\Service\EventSlug;
use App\Service\SlugRandomize;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     *
     * @param EventRepository $eventRepository
     *
     * @return Response
     */
    public function indexAction(EventRepository $eventRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'lastEvents'        => $eventRepository->findLastPublicEvents(),
            'almostEndedEvents' => $eventRepository->findAlmostEndedPublicEvents(),
        ]);
    }

    /**
     * @Route("/create", name="event_create")
     *
     * @param SlugRandomize $slugRandomize
     * @param Request       $request
     * @param EventSlug     $eventSlug
     *
     * @return Response
     * @throws \Exception
     */
    public function create(SlugRandomize $slugRandomize, Request $request, EventSlug $eventSlug): Response
    {
        $event = new Event();
        $form  = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $event->setSlug($eventSlug->create($event->getLabel()));
            $slugRandomize->randomizeSlug($event);
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('event_view', ['slug' => $event->getSlug()]);
        }

        return $this->render('event/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/{slug}", name="event_view")
     *
     * @param Event $event
     *
     * @return Response
     */
    public function show(Event $event): Response
    {
        return $this->render('event/view.html.twig', ['event' => $event]);
    }
}
