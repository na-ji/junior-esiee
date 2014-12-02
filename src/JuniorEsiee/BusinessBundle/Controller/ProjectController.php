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
		$projects          = $this->em->getRepository('JuniorEsieeBusinessBundle:Project')->queryWaitingCommercial();

        return $this->listProject($projects, $page, 'Boîte de réception');
	}

    /**
     * @Secure(roles="ROLE_COMMERCIAL, ROLE_ADMIN")
     */
	public function inProgressAction($page, $sort, $direction)
	{
		$_GET['sort']      = $sort;
		$_GET['direction'] = $direction;
		$user              = $this->security->getToken()->getUser();
		$projects          = $this->em->getRepository('JuniorEsieeBusinessBundle:Project')->queryInProgress($user);

        return $this->listProject($projects, $page, 'AP en cours');
	}

    /**
     * @Secure(roles="ROLE_COMMERCIAL, ROLE_CHARGE")
     */
	public function waitingCommercialAction($page, $sort, $direction)
	{
		$_GET['sort']      = $sort;
		$_GET['direction'] = $direction;
		$projects          = $this->em->getRepository('JuniorEsieeBusinessBundle:Project')->queryWaitingCommercial();

        return $this->listProject($projects, $page, 'AP en attente de suivi commercial');
	}

    /**
     * @Secure(roles="ROLE_COMMERCIAL, ROLE_CHARGE")
     */
	public function waitingInformationAction($page, $sort, $direction)
	{
		$_GET['sort']      = $sort;
		$_GET['direction'] = $direction;
		$projects          = $this->em->getRepository('JuniorEsieeBusinessBundle:Project')->queryMissingInfo();

        return $this->listProject($projects, $page, 'AP en attente de renseignements supplémentaires');
	}

    /**
     * @Secure(roles="ROLE_COMMERCIAL")
     */
	public function waitingStudentsAction($page, $sort, $direction)
	{
		$_GET['sort']      = $sort;
		$_GET['direction'] = $direction;
		$projects          = $this->em->getRepository('JuniorEsieeBusinessBundle:Project')->queryWaitingStudents();

        return $this->listProject($projects, $page, 'AP en attente d’intervenant');
	}

    /**
     * @Secure(roles="ROLE_COMMERCIAL, ROLE_CHARGE")
     */
	public function abortedAction($page, $sort, $direction)
	{
		$_GET['sort']      = $sort;
		$_GET['direction'] = $direction;
		$projects          = $this->em->getRepository('JuniorEsieeBusinessBundle:Project')->queryAborted();

        return $this->listProject($projects, $page, 'AP Avortés');
	}

    /**
     * @Secure(roles="ROLE_COMMERCIAL")
     */
	public function closedAction($page, $sort, $direction)
	{
		$_GET['sort']      = $sort;
		$_GET['direction'] = $direction;
		$projects          = $this->em->getRepository('JuniorEsieeBusinessBundle:Project')->queryClosed();

        return $this->listProject($projects, $page, 'AP clôturés');
	}

	public function listProject($projects, $page, $title)
	{
        $pagination = $this->paginator->paginate($projects, $page, 15);

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
     * @Secure(roles="ROLE_COMMERCIAL, ROLE_CHARGE")
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
			'referer' => $this->request->headers->get('referer'),
		);
	}

    /**
     * @Secure(roles="ROLE_COMMERCIAL, ROLE_ADMIN")
     */
	public function abortAction(Project $project)
	{
		$project->setState(Project::STATE_ABORTED);
		$this->em->persist($project);
		$this->em->flush();

		$this->request->getSession()->getFlashBag()->add('success', 'Votre appel à projet a bien été avorté.');

		return $this->redirect($this->generateUrl('je_business_project_aborted'));
	}

	/**
	 * @Template
	 * @Secure(roles="ROLE_COMMERCIAL, ROLE_ADMIN")
	 */
	public function abortConfirmationAction(Project $project)
	{
		return array(
			'project' => $project,
			'referer' => $this->request->headers->get('referer'),
		);
	}

    /**
     * @Secure(roles="ROLE_COMMERCIAL, ROLE_ADMIN")
     */
	public function closeAction(Project $project)
	{
		$project->setState(Project::STATE_CLOSED);
		$this->em->persist($project);
		$this->em->flush();

		$this->request->getSession()->getFlashBag()->add('success', 'L\'appel à projet a bien été clôturé.');

		return $this->redirect($this->generateUrl('je_business_project_closed'));
	}

	/**
	 * @Template
	 * @Secure(roles="ROLE_COMMERCIAL, ROLE_ADMIN")
	 */
	public function closeConfirmationAction(Project $project)
	{
		return array(
			'project' => $project,
			'referer' => $this->request->headers->get('referer'),
		);
	}
}