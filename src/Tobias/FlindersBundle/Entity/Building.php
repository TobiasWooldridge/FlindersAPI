<?php

namespace Tobias\FlindersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
* Building
*
* @ORM\Table()
* @ORM\Entity
*/
class Building
{
    /**
      * @var string
      *
      * @ORM\Column(name="id", type="string", length=10)
      * @ORM\Id
      */
    private $id;

    /**
      * @var string
      *
      * @ORM\Column(name="name", type="string", length=255)
      */
    private $name = "";

    /**
     * Set id
     *
     * @param string $id
     * @return Building
     */
    public function setId($id)
    {
        $this->id = $id;
    
        return $this;
    }

    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Building
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
}