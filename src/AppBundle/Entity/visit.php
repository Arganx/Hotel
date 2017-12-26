<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * visit
 *
 * @ORM\Table(name="visit")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\visitRepository")
 */
class visit
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="guest", type="integer")
     */
    private $guest;

    /**
     * @var int
     *
     * @ORM\Column(name="room", type="integer")
     */
    private $room;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", nullable=true)
     */
    private $price;

    /**
     * @var int
     *
     * @ORM\Column(name="extraBeds", type="integer")
     */
    private $extraBeds;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="datetime")
     *
     * @Assert\Type(
     *      type = "\DateTime",
     *      message = "Prosze podać date",
     * )
     * @Assert\GreaterThanOrEqual(
     *      value = "today",
     *      message = "Nie można zarezerwować wizyty z datą wcześniejszą niż dzisiejsza"
     * )
     *
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="datetime")
     * @Assert\Type(
     *      type = "\DateTime",
     *      message = "Prorze podać date",
     * )
     * @Assert\GreaterThanOrEqual(
     *      value = "today",
     *      message = "Nie można zarezerwować wizyty z datą wcześniejszą niż dzisiejsza"
     * )
     * @Assert\Expression(
     *     "this.getEndDate() >= this.getStartdate()",
     *     message="Data wyjazdu musi byc późniejsza niż data przyjazdu"
     * )
     *
     */
    private $endDate;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set guest
     *
     * @param integer $guest
     *
     * @return visit
     */
    public function setGuest($guest)
    {
        $this->guest = $guest;

        return $this;
    }

    /**
     * Get guest
     *
     * @return int
     */
    public function getGuest()
    {
        return $this->guest;
    }

    /**
     * Set room
     *
     * @param integer $room
     *
     * @return visit
     */
    public function setRoom($room)
    {
        $this->room = $room;

        return $this;
    }

    /**
     * Get room
     *
     * @return int
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return visit
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set extraBeds
     *
     * @param integer $extraBeds
     *
     * @return visit
     */
    public function setExtraBeds($extraBeds)
    {
        $this->extraBeds = $extraBeds;

        return $this;
    }

    /**
     * Get extraBeds
     *
     * @return int
     */
    public function getExtraBeds()
    {
        return $this->extraBeds;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return visit
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return visit
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }
}

