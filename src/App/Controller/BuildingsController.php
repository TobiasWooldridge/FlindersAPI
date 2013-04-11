<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use JMS\DiExtraBundle\Annotation as DI;


/**
 * Buildings controller.
 */
class BuildingsController
{
    /**
     * @DI\Inject("app.entity.building_repository")
     */
    protected $repository;

	/**
     * @DI\Inject("serializer")
	 */
    protected $serializer;

    public function indexAction($_format)
    {
        $buildings = $this->repository->findAll();

        $serialized = $this->serializer->serialize($buildings, $_format);

        $response = new Response($serialized);

        return $response;
    }

    public function showAction($id, $_format)
    {
        $building = $this->repository->findOneById($id);

        if (!$building) throw new NotFoundHttpException("The resource was not found");

        $serialized = $this->serializer->serialize($building, $_format);

        $response = new Response($serialized);

        return $response;
    }
}
