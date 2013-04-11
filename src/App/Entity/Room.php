<?php

namespace App\Entity;

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
}