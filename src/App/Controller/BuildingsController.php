<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

/**
 * Buildings controller.
 */
class BuildingsController extends FOSRestController
{
    /**
     * @DI\Inject("app.entity.building_repository")
     */
    protected $buildingRepository;

    /**
     * @DI\Inject("fos_rest.view_handler")
     */
    protected $view_handler;


    public function indexAction($_format)
    {
        $buildings = $this->buildingRepository->findAll();

        $view = $this->view($buildings, 200);

        return $this->handleView($view);
    }

    public function showAction($id)
    {
        $building = $this->buildingRepository->findOneById($id);

        // if (!$building) throw new NotFoundHttpException("The resource was not found");

        // $serialized = $this->serializer->serialize($building, $_format);

        // $response = new Response($serialized);

        // return $response;


        $view = View::create()
          ->setStatusCode(200)
          ->setData($building);

        return $this->get('fos_rest.view_handler')->handle($view);
    }

}
