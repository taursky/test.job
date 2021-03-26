<?php


namespace App\Service;


use App\Entity\Flight;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use function Doctrine\ORM\QueryBuilder;

class FlightService
{
    protected $em;

    protected $mailer;

    public function __construct(EntityManagerInterface $em, MailerInterface $mailer)
    {
        $this->em = $em;
        $this->mailer = $mailer;
    }

    public function flightCancellation()
    {
        $flights = $this->getCencelatedFlights();

        if (!empty($flights)) {
            $flightNumbers =[];
            foreach ($flights as $flight) {
                $flightNumbers[] = $flight['flightNumber'];
            }
            $emails = $this->selectEmails($flightNumbers);
            if (!empty($emails)) {
                $this->sendEmails($emails);
            }
        }

        return 1;
    }

    private function getCencelatedFlights()
    {
        $qb = $this->em->getRepository(Flight::class)->createQueryBuilder('f');
        $qb->andWhere('f.flightStatus = :flightStatus')
            ->select('f.number as flightNumber')
            ->groupBy('f.number')
            ->setParameters([
                'flightStatus' => false
            ])
        ;

        return $qb->getQuery()->getResult();
    }

    private function selectEmails($flightNumbers)
    {
        $qb = $this->em->getRepository(Flight::class)->createQueryBuilder('f');
        $qb->andWhere($qb->expr()->in('f.number', ':numbers'))
            ->andWhere($qb->expr()->isNotNull('f.passenger'))
            ->leftJoin('f.passenger', 'p')
            ->select('p.email as email')
            ->groupBy('p.email')
            ->setParameter('numbers', $flightNumbers)
        ;

        return $qb->getQuery()->getArrayResult();
    }

    private function sendEmails($emails)
    {
        $emailsTo = '';
        $i = 1;
        foreach ($emails as $email) {
            if ($i == count($emails)) {
                $emailsTo .= $email['email'];
            } else {
                $emailsTo .= $email['email'] . ',';
            }
            $i++;
        }

        $email = (new Email())
            ->from('noreply@localhost.com')
            ->to($emailsTo)
            ->cc('taursky@mail.ru')
            //->bcc('bcc@example.com')
//            ->replyTo('taursky@mail.ru')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Flight has been cancelled')
            ->text('Your flight has been cancelled, please contact your company to return your ticket or check in for another flight.')
//            ->html('<p>See Twig integration for better HTML integration!</p>')
        ;

        try {
            $this->mailer->send($email);
            //do something
        } catch (TransportExceptionInterface $e) {//TransportExceptionInterface
            throw new \Exception('mail failed');
            // some error prevented the email sending; display an
            // error message or try to resend the message
        }
    }
}