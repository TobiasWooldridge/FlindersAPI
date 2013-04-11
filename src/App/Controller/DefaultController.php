<?php

namespace App\Controller;

class DefaultController
{
	/**
	 * @DI\Inject("doctrine.orm.entity_manager")
	 * @var \Doctrine\ORM\EntityManager
	 */
    protected $em;

    /**
     * Homepage
     */
    public function homepageAction()
    {
    }
}
