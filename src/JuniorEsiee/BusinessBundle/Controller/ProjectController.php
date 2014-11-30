<?php

namespace JuniorEsiee\BusinessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JuniorEsiee\BusinessBundle\Entity\Project;
use JuniorEsiee\BusinessBundle\Form\ProjectType;
use Symfony\Component\HttpFoundation\Request;

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
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var Request
     */
    private $request;

	public function myProjectAction($page, $sort, $direction)
	{
		$_GET['sort']      = $sort;
		$_GET['direction'] = $direction;
		$projects          = $this->em->getRepository('JuniorEsieeBusinessBundle:Project')->queryAll();

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
	public function newAction(Request $request)
	{
		$project = new Project();
		$form = $this->formFactory->create('junioresiee_businessbundle_project', $project);

		if ($this->request->getMethod() == 'POST') {
			$form->handleRequest($this->request);

			if ($form->isValid()) {
				$this->em->persist($project);
				$this->em->flush();
				$request->getSession()->getFlashBag()->add('success', 'Votre appel à projet a bien été enregistré.');

				return $this->redirect($this->generateUrl('je_business_project_edit', array('id' => $project->getId())));
			}
		}

		return array(
			'form' => $form->createView(),
		);
	}

	/**
	 * @Template
	 */
	public function editAction(Project $project, Request $request)
	{
		$form = $this->formFactory->create('junioresiee_businessbundle_project', $project);

		if ($this->request->getMethod() == 'POST') {
			$form->handleRequest($this->request);

			if ($form->isValid()) {
				$this->em->persist($project);
				$this->em->flush();
				$request->getSession()->getFlashBag()->add('success', 'Votre appel à projet a bien été mis-à-jour.');
			}
		}

		return array(
			'form'    => $form->createView(),
			'project' => $project,
		);
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