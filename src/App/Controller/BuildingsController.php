<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use JMS\DiExtraBundle\Annotation as DI;

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

    public function indexAction()
    {
    	$buildings = $this->repository->findAll();

        $serialized = $this->serializer->serialize($buildings, 'json');

    	$response = new Response($serialized);

        return $response;
    }
}
