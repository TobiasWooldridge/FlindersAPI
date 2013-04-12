<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use JMS\DiExtraBundle\Annotation as DI;


/**
 * RoomBookings controller.
 */
class RoomBookingsController
{
    /**
     * @DI\Inject("app.entity.roomBooking_repository")
     */
    protected $repository;

	/**
     * @DI\Inject("serializer")
	 */
    protected $serializer;

    public function indexAction($_format)
    {
        $roomBooking = $this->repository->findAll();

        $serialized = $this->serializer->serialize($roomBooking, $_format);

        $response = new Response($serialized);

        return $response;
    }

    public function showAction($id, $_format)
    {
        $roomBooking = $this->repository->findOneById($id);

        if (!$roomBooking) throw new NotFoundHttpException("The resource was not found");

        $serialized = $this->serializer->serialize($roomBooking, $_format);

        $response = new Response($serialized);

        return $response;
    }
}
