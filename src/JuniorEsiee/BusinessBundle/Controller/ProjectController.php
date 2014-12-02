<?php

namespace JuniorEsiee\BusinessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JuniorEsiee\BusinessBundle\Entity\Project;
use JuniorEsiee\BusinessBundle\Form\ProjectType;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;

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

    /**
     * @var SecurityContext
     */
    private $security;

    /**
     * @Secure(roles="ROLE_COMMERCIAL")
     */
	public function myProjectAction($page, $sort, $direction)
	{
		$_GET['sort']      = $sort;
		$_GET['direction'] = $direction;
		$user              = $this->security->getToken()->getUser();
		$projects          = $this->em->getRepository('JuniorEsieeBusinessBundle:Project')->queryUsers($user);

        return $this->listProject($projects, $page, 'Mes appels à projets');
	}

    /**
     * @Secure(roles="ROLE_COMMERCIAL, ROLE_ADMIN")
     */
	public function inboxAction($page, $sort, $direction)
	{
		$_GET['sort']      = $sort;
		$_GET['direction'] = $direction;
		$projects          = $this->em->getRepository('JuniorEsieeBusinessBundle:Project')->queryWithoutCommercial();

        return $this->listProject($projects, $page, 'Boîte de réception');
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
     * @Secure(roles="ROLE_COMMERCIAL")
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
     * @Secure(roles="ROLE_COMMERCIAL")
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

				return $this->redirect($this->generateUrl('je_business_project_show', array('id' => $project->getId())));
			}
		}

		return array(
			'form'    => $form->createView(),
			'project' => $project,
		);
	}

	/**
	 * @Template
     * @Secure(roles="ROLE_COMMERCIAL")
	 */
	public function showAction(Project $project)
	{
		return array(
			'project' => $project,
		);
	}

    /**
     * @Secure(roles="ROLE_COMMERCIAL, ROLE_ADMIN")
     */
	public function deleteAction(Project $project)
	{
		$this->em->remove($project);
		$this->em->flush();

		$this->request->getSession()->getFlashBag()->add('success', 'Votre appel à projet a bien été supprimé.');

		return $this->redirect($this->generateUrl('je_business_my_project'));
	}

	/**
	 * @Template
	 * @Secure(roles="ROLE_COMMERCIAL, ROLE_ADMIN")
	 */
	public function deleteConfirmationAction(Project $project)
	{
		return array(
			'project' => $project,
		);
	}
}