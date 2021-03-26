<?php


namespace App\Service;


use App\Entity\Flight;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class CreateFlight
{
    private $em;

    private $place;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->place = 150;
    }

    public function createFlight()
    {
        $this->createUsers();

        $flightNumber = $this->flightNumber();

        for ($i = 0; $i < $this->place; $i++) {
            $flight = new Flight();
            $flight->setNumber($flightNumber);
            $flight->setCreateDate(new  \DateTime());
            $flight->setPlace($i + 1);
            $flight->setStatus(Flight::FREE);
            $flight->setEndBookingTime(new \DateTime('+1week'));
            $flight->setflightStatus(true);

            $this->em->persist($flight);
        }
        $this->em->flush();

        return 1;
    }

    private function createUsers()
    {
        for ($i = 0; $i < $this->place; $i++) {
            $user = new User();
            $user->setName( 'ivan_' . $i);
            $user->setIp('localhost');
            $user->setStatus(true);
            $user->setEmail('ivan_' . $i . '@mail.ru');
            $user->setCreateDate((new \DateTime())->format('d.m.Y H:i:s'));

            $this->em->persist($user);
        }
        $this->em->flush();

        return 1;
    }

    private function flightNumber()
    {
        return 6942;
    }
}