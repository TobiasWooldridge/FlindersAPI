<?php

namespace Tobias\FlindersBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\View\View;
use JMS\DiExtraBundle\Annotation as DI;

class BuildingRestController extends FOSRestController
{
    /**
     * @DI\Inject("doctrine.orm.entity_manager")
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @Route("/api/buildings")
     * 
     * @param string $buildingId
     * @return Response
     */
    public function indexAction()
    {
        $building = $this->em->getRepository('TobiasFlindersBundle:Building')->findAll();
        
        $view = $this->view($building, 200)
            ->setTemplate("TobiasFlindersBundle:BuildingRest:index.html.twig")
            ->setTemplateVar('buildings');

        return $this->handleView($view);
    }

    /**
     * @Route("/api/buildings/{buildingId}")
     * 
     * @param string $buildingId
     * @return Response
     */
    public function showAction($buildingId)
    {
        $building = $this->em->getRepository('TobiasFlindersBundle:Building')->findOneById($buildingId);
        
        $view = $this->view($building, 200)
            ->setTemplate("TobiasFlindersBundle:BuildingRest:show.html.twig")
            ->setTemplateVar('building');

        return $this->handleView($view);
    }
}
