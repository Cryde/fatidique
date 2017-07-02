<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\Repository\EventRepository;
use AppBundle\Service\SlugRandomize;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EventController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(EventRepository $eventRepository)
    {
        $lastEvents = $eventRepository->findLastPublicEvents();

        return $this->render('@App/event/index.html.twig', ['lastEvents' => $lastEvents]);
    }

    /**
     * @Route("/create", name="event_create")
     */
    public function createAction(SlugRandomize $slugRandomize, Request $request)
    {
        $event = new Event();
        $form  = $this->createForm(\AppBundle\Form\Event::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $slugRandomize->setEvent($event)->randomizeSlug();
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('event_view', ['slug' => $event->getSlug()]);
        }

        return $this->render('@App/event/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/{slug}", name="event_view")
     * @ParamConverter("Event", options={"mapping": {"slug": "slug"}})
     *
     * @param Event $event
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Event $event)
    {
        return $this->render('@App/event/view.html.twig', ['event' => $event]);
    }
}
