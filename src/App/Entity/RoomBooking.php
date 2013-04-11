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

}
    