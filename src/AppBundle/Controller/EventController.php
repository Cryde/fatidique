<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\Repository\EventRepository;
use AppBundle\Form\Type\EventType;
use AppBundle\Service\SlugRandomize;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EventController extends Controller
{
    /**
     * @Route("/", name="homepage")
     *
     * @param EventRepository $eventRepository
     *
     * @return Response
     */
    public function indexAction(EventRepository $eventRepository)
    {
        return $this->render(
            '@App/event/index.html.twig',
            [
                'lastEvents'        => $eventRepository->findLastPublicEvents(),
                'almostEndedEvents' => $eventRepository->findAlmostEndedPublicEvents(),
            ]
        );
    }

    /**
     * @Route("/create", name="event_create")
     *
     * @param SlugRandomize $slugRandomize
     * @param Request       $request
     *
     * @return Response
     */
    public function createAction(SlugRandomize $slugRandomize, Request $request)
    {
        $event = new Event();
        $form  = $this->createForm(EventType::class, $event);

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
     * @return Response
     */
    public function viewAction(Event $event)
    {
        return $this->render('@App/event/view.html.twig', ['event' => $event]);
    }
}
