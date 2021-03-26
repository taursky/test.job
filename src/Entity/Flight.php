<?php

namespace App\Entity;

use App\Repository\FlightRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FlightRepository::class)
 */
class Flight
{
    const FREE = 'free';

    const RESERVE = 'reserve';

    const CANCEL_RESERVATION = 'cancel_reservation';

    const TICKET_BOUGHT = 'buy_ticket';

    const RETURNED_TICKET = 'return_ticket';

    const SECRET_KEY = 'Aic1yaelohzomib7Taroow3v';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $number;

    /**
     * @ORM\Column(type="integer")
     */
    private $place;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $purchaseDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateDate;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $history = [];

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endBookingTime;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $flightStatus;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="flights")
     */
    private $passenger;


    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getPlace(): ?int
    {
        return $this->place;
    }

    public function setPlace(int $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(?\DateTimeInterface $createDate): self
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getPurchaseDate(): ?\DateTimeInterface
    {
        return $this->purchaseDate;
    }

    public function setPurchaseDate(\DateTimeInterface $purchaseDate): self
    {
        $this->purchaseDate = $purchaseDate;

        return $this;
    }

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->updateDate;
    }

    public function setUpdateDate(?\DateTimeInterface $updateDate): self
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    public function getHistory(): ?array
    {
        return $this->history;
    }

    public function setHistory(?array $history): self
    {
        $this->history = $history;

        return $this;
    }

    public function getEndBookingTime(): ?\DateTimeInterface
    {
        return $this->endBookingTime;
    }

    public function setEndBookingTime(?\DateTimeInterface $endBookingTime): self
    {
        $this->endBookingTime = $endBookingTime;

        return $this;
    }

    public function getflightStatus(): ?bool
    {
        return $this->flightStatus;
    }

    public function setflightStatus(?bool $flightStatus): self
    {
        $this->flightStatus = $flightStatus;

        return $this;
    }

    public function getPassenger(): ?User
    {
        return $this->passenger;
    }

    public function setPassenger(?User $passenger): self
    {
        $this->passenger = $passenger;

        return $this;
    }
}
