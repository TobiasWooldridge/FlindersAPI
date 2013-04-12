<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * RoomBookings controller.
 */
class RoomBookingsController
{
    /**
     * @DI\Inject("doctrine.orm.entity_manager")
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

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
        $roomBookings = $this->em
        ->createQuery(
            'SELECT b.id, b.start, b.end, b.description, IDENTITY(b.room) room, b.cancelled
            FROM App\Entity\RoomBooking b'
            )
        ->execute();

        $serialized = $this->serializer->serialize($roomBookings, $_format);

        $response = new Response($serialized);

        return $response;
    }

    public function showAction($id, $_format)
    {
        $roomBooking = $this->repository->findOneById($id);

        $roomBookings = $this->em
        ->createQuery(
            'SELECT b.id, b.start, b.end, b.description, IDENTITY(b.room) room, b.cancelled
            FROM App\Entity\RoomBooking b
            WHERE b.id = :id'
            )
        ->setParameter('id', $id)
        ->getResult();

        $serialized = $this->serializer->serialize($roomBooking, $_format);

        $response = new Response($serialized);

        return $response;
    }
}
