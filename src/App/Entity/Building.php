<?php

namespace App\Entity;

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
}