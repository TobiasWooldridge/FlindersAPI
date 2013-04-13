<?php

namespace Tobias\FlindersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
* Room
*
* @ORM\Table()
* @ORM\Entity
*/
class Room
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
      * @var string
      *
      * @ORM\Column(name="code", type="string", length=10)
      */
    private $code;

    /**
      * @var string
      *
      * @ORM\Column(name="name", type="string", length=255)
      */
    private $name;


    /**
      * @var integer
      *
      * @ORM\Column(name="capacity", type="integer")
      */
    private $capacity;


    /**
      * @var Building
      *
      * @ORM\ManyToOne(targetEntity="Building")
      * @ORM\JoinColumn(name="building_id", referencedColumnName="id")
      */
    private $building;

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
     * Get global code
     *
     * @return string 
     */
    public function getGlobalCode()
    {
        return $this->building->getId() . $this->code;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Room
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Room
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set capacity
     *
     * @param integer $capacity
     * @return Room
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;
    
        return $this;
    }

    /**
     * Get capacity
     *
     * @return integer 
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * Set building
     *
     * @param \Tobias\FlindersBundle\Entity\Building $building
     * @return Room
     */
    public function setBuilding(\Tobias\FlindersBundle\Entity\Building $building = null)
    {
        $this->building = $building;
    
        return $this;
    }

    /**
     * Get building
     *
     * @return \Tobias\FlindersBundle\Entity\Building 
     */
    public function getBuilding()
    {
        return $this->building;
    }
}