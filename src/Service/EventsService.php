<?php


namespace App\Service;


use App\Entity\Flight;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class EventsService
{
    private $em;


    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param $request
     * @param $status
     * @param null $orStatus
     * @return int|mixed|string|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getPlace($request, $status, $orStatus = null)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->em->getRepository(Flight::class)->createQueryBuilder('f');
        $qb->andWhere('f.number = :number')
            ->andWhere('f.place = :place')
            ->andWhere('f.flightStatus = :true')
            ->setParameters([
                'number' => $request->get('flight'),
                'place' => $request->get('place'),
                'true' => true,
            ]);
        if ($orStatus){
            $qb->andWhere($qb->expr()->orX(
                'f.status = :orStatus',
                'f.status = :status'

            ))
            ->setParameter('orStatus', $orStatus)
            ->setParameter('status', $status)
            ;
        } else {
            $qb->andWhere('f.status = :status')
            ->setParameter('status', $status);
        }

        return $qb->getQuery()->getOneOrNullResult();

    }

    /**
     * @param $request
     * @return array[]
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function reserve($request)
    {
        $flight = $this->getPlace($request, Flight::FREE);

        if ($flight) {
            $flight->setStatus(Flight::RESERVE);
            $flight->setPurchaseDate(new \DateTime());
            $flight->setUpdateDate(new \DateTime());
            $flight->setPassenger($this->em->getRepository(User::class)->find($request->get('passenger')));
            $this->em->flush();

            return [
                'data' => [
                    "flight_id" => $flight->getNumber(),
                    "triggered_at" => $flight->getPurchaseDate()->format('d.m.Y'),
                    "event" => "flight_reserve_sales_completed",
                    "secret_key" => Flight::SECRET_KEY,
                ],

            ];
        }

        return [
            'data' => [
                "flight_id" => $request->get('flight'),
                "triggered_at" => 'this reservation on flight is made or ticket bought.',
                "event" => $request->get('event'),
                'place' => $request->get('place'),
                "secret_key" => Flight::SECRET_KEY,
            ],
        ];
    }

    /**
     * @param $request
     * @return array[]
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function cancelReservation($request)
    {
        /** @var Flight $flight */
        $flight = $this->getPlace($request, Flight::RESERVE);
        if (!empty($flight)) {
            $flight->setStatus(Flight::FREE);
            $flight->setUpdateDate(new \DateTime());
            $flight->setPassenger(null);
            $this->em->flush();

            return [
                'data' => [
                    "flight_id" => $flight->getNumber(),
                    'place' => $flight->getPlace(),
                    "triggered_at" => $flight->getPurchaseDate()->format('d.m.Y'),
                    "event" => "flight_cancel_reserve_completed",
                    "secret_key" => Flight::SECRET_KEY,
                ],];
        }

        return [
            'data' => [
                "flight_id" => $request->get('flight'),
                "triggered_at" => 'The reservation from this place is already cancelled.',
                "event" => $request->get('event'),
                'place' => $request->get('place'),
                "secret_key" => Flight::SECRET_KEY,
            ],
        ];
    }

    /**
     * @param $request
     * @return array[]
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function buyTicket($request)
    {
        /** @var Flight $flight */
        $flight = $this->getPlace($request, Flight::FREE, Flight::RESERVE);
        if (!empty($flight)) {
            $flight->setStatus(Flight::TICKET_BOUGHT);
            $flight->setPurchaseDate(new \DateTime());
            $flight->setUpdateDate(new \DateTime());
            $flight->setPassenger($this->em->getRepository(User::class)->find($request->get('passenger')));
            $this->em->flush();
            return [
                'data' => [
                    "flight_id" => $flight->getNumber(),
                    "triggered_at" => $flight->getPurchaseDate()->format('d.m.Y'),
                    "event" => "flight_ticket_sales_completed",
                    'passenger' => $flight->getPassenger()->getName(),
                    "secret_key" => Flight::SECRET_KEY,
                ],];
        }

        return [
            'data' => [
                "flight_id" => $request->get('flight'),
                "triggered_at" => 'this reservation on flight is made or ticket bought.',
                "event" => $request->get('event'),
                'place' => $request->get('place'),
                "secret_key" => Flight::SECRET_KEY,
            ],
        ];
    }

    /**
     * @param $request
     * @return array[]
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function returnTicket($request)
    {
        $flight = $this->getPlace($request, Flight::TICKET_BOUGHT);
        if (!empty($flight)) {
            $flight->setStatus(Flight::FREE);
            $flight->setPurchaseDate(new \DateTime());
            $flight->setUpdateDate(new \DateTime());
            $flight->setPassenger(null);
            $this->em->flush();

            return [
                'data' => [
                    "flight_id" => $flight->getNumber(),
                    "triggered_at" => $flight->getPurchaseDate()->format('d.m.Y'),
                    "event" => "flight_ticket_return_completed",
                    "secret_key" => Flight::SECRET_KEY,
                ],];
        }

        return [
            'data' => [
                "flight_id" => $request->get('flight'),
                "triggered_at" => 'No ticket found.',
                "event" => $request->get('event'),
                'place' => $request->get('place'),
                "secret_key" => Flight::SECRET_KEY,
            ],
        ];
    }

    /**
     * @param $request
     * @return array[]
     */
    public function notFound($request)
    {

        return [
            'data' => [
                "flight_id" => $request->get('flight'),
                "triggered_at" => 'No ticket found.',
                "event" => $request->get('event'),
                'place' => $request->get('place'),
                "secret_key" => Flight::SECRET_KEY,
            ],
        ];
    }
}