<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
* RoomBooking
*
* @ORM\Table()
* @ORM\Entity
* @ORM\Entity(repositoryClass="App\Entity\DefaultRepository")
*/
class RoomBooking
{
    /**
      * @var integer
      *
      * @ORM\Column(name="id", type="integer")
      * @ORM\Id
      * @ORM\GeneratedValue(strategy="AUTO")
      */
    private $id;

    /**
      * @var \DateTime
      *
      * @ORM\Column(name="start", type="datetime")
      */
    private $start;

    /**
      * @var \DateTime
      *
      * @ORM\Column(name="end", type="datetime")
      */
    private $end;


    /**
      * @var string
      *
      * @ORM\Column(name="description", type="text")
      */
    private $description;

    /**
      * @var boolean
      *
      * @ORM\Column(name="cancelled", type="boolean")
      */
    private $cancelled;

    /**
      * @var Room
      *
      * @ORM\ManyToOne(targetEntity="Room")
      * @ORM\JoinColumn(name="room_id", referencedColumnName="id")
      */
    private $room;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set start
     *
     * @param \DateTime $start
     * @return RoomBooking
     */
    public function setStart($start)
    {
        $this->start = $start;
    
        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime 
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     * @return RoomBooking
     */
    public function setEnd($end)
    {
        $this->end = $end;
    
        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime 
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return RoomBooking
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set room
     *
     * @param \App\Entity\Room $room
     * @return RoomBooking
     */
    public function setRoom(\App\Entity\Room $room = null)
    {
        $this->room = $room;
    
        return $this;
    }

    /**
     * Get room
     *
     * @return \App\Entity\Room 
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * Set cancelled
     *
     * @param boolean $cancelled
     * @return RoomBooking
     */
    public function setCancelled($cancelled)
    {
        $this->cancelled = $cancelled;
    
        return $this;
    }

    /**
     * Get cancelled
     *
     * @return boolean 
     */
    public function getCancelled()
    {
        return $this->cancelled;
    }
}