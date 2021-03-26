<?php

namespace App\Controller;

use App\Entity\Flight;
use App\Service\EventsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RestApiController
 * @package App\Controller
 *
 */
class RestApiController extends AbstractController
{

    protected $eventService;

    /**
     * RestApiController constructor.
     * @param EventsService $eventService
     */
    public function __construct(EventsService $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * @Route("v1/callback/events", name="callback_events")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function index(Request $request): Response
    {
        if (!$request->get('secret_key') || $request->get('secret_key') != Flight::SECRET_KEY) {
            throw new \Exception('access denied');
        }
        switch ($request->get('event')) {
            case Flight::CANCEL_RESERVATION:
                $result = $this->eventService->cancelReservation($request);
                break;
            case Flight::RETURNED_TICKET:
                $result = $this->eventService->returnTicket($request);
                break;
            case Flight::RESERVE:
                $result = $this->eventService->reserve($request);
                break;
            case Flight::TICKET_BOUGHT:
                $result = $this->eventService->buyTicket($request);
                break;
            default:
                $result = $this->eventService->notFound($request);
                break;
        }

        return $this->json($result);
    }
}
