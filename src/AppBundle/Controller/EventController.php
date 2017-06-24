<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EventController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('@App/event/index.html.twig');
    }

    /**
     * @Route("/create", name="event_create")
     */
    public function createAction(Request $request)
    {
        $event = new Event();
        $form  = $this->createForm(\AppBundle\Form\Event::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('event_view', ['id' => $event->getId()]);
        }

        return $this->render('@App/event/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/view/{id}", name="event_view")
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
