<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use JMS\DiExtraBundle\Annotation as DI;


/**
 * Rooms controller.
 */
class RoomsController
{
    /**
     * @DI\Inject("app.entity.room_repository")
     */
    protected $repository;

	/**
     * @DI\Inject("serializer")
	 */
    protected $serializer;

    public function indexAction($_format)
    {
        $rooms = $this->repository->findAll();

        $serialized = $this->serializer->serialize($rooms, $_format);

        $response = new Response($serialized);

        return $response;
    }

    public function showAction($id, $_format)
    {
        $room = $this->repository->findOneById($id);

        if (!$room) throw new NotFoundHttpException("The resource was not found");

        $serialized = $this->serializer->serialize($room, $_format);

        $response = new Response($serialized);

        return $response;
    }
}
