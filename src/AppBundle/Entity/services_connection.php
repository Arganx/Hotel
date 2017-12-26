<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * services_connection
 *
 * @ORM\Table(name="services_connection")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\services_connectionRepository")
 */
class services_connection
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
     * @ORM\Column(name="visit_id", type="integer")
     */
    private $visitId;

    /**
     * @var int
     *
     * @ORM\Column(name="service_id", type="integer")
     */
    private $serviceId;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     *
     * @Assert\Type(
     *      type = "\DateTime",
     *      message = "Prosze podać date",
     * )
     * @Assert\GreaterThanOrEqual(
     *      value = "today",
     *      message = "Nie można skożystać z usługi z datą wcześniejszą niż dzisiejsza"
     * )
     *
     */
    private $date;



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
     * Set visitId
     *
     * @param integer $visitId
     *
     * @return services_connection
     */
    public function setVisitId($visitId)
    {
        $this->visitId = $visitId;

        return $this;
    }

    /**
     * Get visitId
     *
     * @return int
     */
    public function getVisitId()
    {
        return $this->visitId;
    }

    /**
     * Set serviceId
     *
     * @param integer $serviceId
     *
     * @return services_connection
     */
    public function setServiceId($serviceId)
    {
        $this->serviceId = $serviceId;

        return $this;
    }

    /**
     * Get serviceId
     *
     * @return int
     */
    public function getServiceId()
    {
        return $this->serviceId;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return services_connection
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    public function __construct()
    {
        $this->date = new \DateTime();
    }
}

