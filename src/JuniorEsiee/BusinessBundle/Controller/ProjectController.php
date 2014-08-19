<?php

namespace JuniorEsiee\BusinessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JuniorEsiee\BusinessBundle\Entity\Project;

class ProjectController extends Controller
{
    /** 
    * @var  EntityManager 
    */
    private $em;

    /**
     * @var Paginator
     */
    private $paginator;

	/**
	 * @Template
	 */
	public function indexAction()
	{
		return array();
	}

	public function myProjectAction($page)
	{
        $projects = $this->em->getRepository('JuniorEsieeBusinessBundle:Project')->queryAll();

        return $this->listProject($projects, $page, 'Mes appels à projets');
	}

	public function listProject($projects, $page, $title)
	{
        $pagination = $this->paginator->paginate($projects, $page, 10);

		return $this->render('JuniorEsieeBusinessBundle:Project:list.html.twig', array(
			'title'    => $title,
			'projects' => $pagination,
		));
	}

	/**
	 * @Template
	 */
	public function createAction()
	{
		return array();
	}

	/**
	 * @Template
	 */
	public function editAction(Project $project)
	{
		return array();
	}

	/**
	 * @Template
	 */
	public function showAction(Project $project)
	{
		return array(
			'project' => $project,
		);
	}

	public function deleteAction(Project $project)
	{
		return array();
	}

	/**
	 * @Template
	 */
	public function deleteConfirmationAction(Project $project)
	{
		return array(
			'project' => $project,
		);
	}
}