<?php

namespace JuniorEsiee\BusinessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JuniorEsiee\BusinessBundle\Entity\Project;
use JuniorEsiee\BusinessBundle\Form\ProjectType;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Application\Sonata\UserBundle\Entity\User;

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
     * @var JuniorEsiee\NotificationBundle\Services\Notificator
     */
    private $notificator;

    /**
     * @var Braincrafted\Bundle\BootstrapBundle\Session\FlashMessage
     */
    private $flash;

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
		$projects          = $this->em->getRepository('JuniorEsieeBusinessBundle:Project')->queryMissingInfo();

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
				$this->flash->success('Votre appel à projet a bien été enregistré.');

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
		    	if ($project->getState() == Project::STATE_WAITING_INFORMATION)
		    		$project->setState(Project::STATE_OPENED);
				$this->em->persist($project);
				$this->em->flush();

				$this->flash->success('Votre appel à projet a bien été mis-à-jour.');

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

		$this->flash->success('L\'appel à projet a bien été supprimé.');

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

		$this->flash->success('Votre appel à projet a bien été avorté.');

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

		$this->flash->success('L\'appel à projet a bien été clôturé.');

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

	public function openEnrollmentAction(Project $project, $type, $open)
	{
		$open = (boolean) $open;
		$ouvert = ($open) ? 'ouvert' : 'fermé';
		if ($project->getState() !== Project::STATE_OPENED)
		{
			$this->flash->error('Vous ne pouvez lancer le recrutement sur cet appel à projet.');
		} else 
		{
			switch($type)
			{
				case 'commercial':
					if (!$this->security->isGranted('ROLE_ADMIN'))
					{
						throw new AccessDeniedException('Accès limité aux administrateurs.');
					}
					if ($project->getCommercialEnrollmentOpen() === $open)
						$this->flash->alert('Le recrutement du commercial était déjà '.$ouvert.'.');
					else {
						$project->setCommercialEnrollmentOpen($open);
						$this->flash->success('Le recrutement du commercial est maintenant '.$ouvert.'.');

						if ($open)
						{
							$commerciaux = $this->em->getRepository('ApplicationSonataUserBundle:User')->findByRoles(array('ROLE_COMMERCIAL', 'ROLE_CHARGE'));
							foreach ($commerciaux as $user) {
								$this->notificator->notify(
									$user, 
									'Une nouvelle étude est disponible <a href="{{ url(\'je_business_project_show\', {id: '.$project->getId().'}) }}">à cette adresse</a>',
									'[Junior] Une nouvelle étude à suivre est disponible',
									'Bonjour,<br /><br />

									Une nouvelle étude à suivre est disponible sur le site. Voici son intitulé :<br />
									<b>'.$project->getTitle().'</b><br />
									'.$project->getDescription().'<br /><br />

									Plus d\'informations <a href="{{ url(\'je_business_project_show\', {id: '.$project->getId().'}) }}">à cette adresse</a>.'
								);
							}
						}
					}
				break;
				case 'implementer':
					if (!$this->security->isGranted('ROLE_CHARGE'))
					{
						throw new AccessDeniedException('Accès limité aux commerciaux.');
					}
					if ($project->getStudentsEnrollmentOpen() === $open)
						$this->flash->alert('Le recrutement des réalisateurs était déjà '.$ouvert.'.');
					else {
						$project->setStudentsEnrollmentOpen($open);
						$this->flash->success('Le recrutement des réalisateurs est maintenant '.$ouvert.'.');

						if ($open)
						{
							if ($project->getSkillCategories()->count() > 0) {
								$users = $this->em->getRepository('ApplicationSonataUserBundle:User')->findWithSkillCategory($project->getSkillCategories());
								foreach ($users as $user) {
									$this->notificator->notify(
										$user, 
										'Une nouvelle étude est disponible <a href="{{ url(\'je_business_project_show\', {id: '.$project->getId().'}) }}">à cette adresse</a>',
										'[Junior] Une nouvelle étude est disponible',
										'Bonjour,<br /><br />

										Une nouvelle étude correspondant à votre domaine de compétence est disponible sur le site. Voici son intitulé :<br />
										<b>'.$project->getTitle().'</b><br />
										'.$project->getDescription().'<br /><br />

										Plus d\'informations <a href="{{ url(\'je_business_project_show\', {id: '.$project->getId().'}) }}">à cette adresse</a>.'
									);
								}
							}
						}
					}
				break;
				default:
					throw $this->createNotFoundException('URL incorrect');
				break;
			}
			$this->em->persist($project);
			$this->em->flush();
		}

		if (null !== $this->request->headers->get('referer'))
			return $this->redirect($this->request->headers->get('referer'));
		else
			return $this->redirect($this->generateUrl('je_business_my_project'));
	}

	public function validateAction(Project $project)
	{
		$validator = $this->get('validator');
		$errors    = $validator->validate($project);

	    if (count($errors) > 0) {
	    	foreach ($errors->getIterator() as $error) {
	    		$this->flash->error($error->getMessage());
	    	}
	    } else {
	    	if ($project->getState() == Project::STATE_WAITING_INFORMATION)
	    	{
	    		$project->setState(Project::STATE_OPENED);
				$this->em->persist($project);
				$this->em->flush();
	    	}
			$this->flash->success('L\'appel à projet est valide.');
	    }

		if (null !== $this->request->headers->get('referer'))
			return $this->redirect($this->request->headers->get('referer'));
		else
			return $this->redirect($this->generateUrl('je_business_project_show', array('id' => $project->getId())));
	}

	public function postulateAction(Project $project, $type)
	{
		if ($project->getState() !== Project::STATE_OPENED)
		{
			$this->flash->error('Vous ne pouvez postuler sur cet appel à projet.');
		} else 
		{
			$user = $this->security->getToken()->getUser();
			switch($type)
			{
				case 'commercial':
					if (!$this->security->isGranted('ROLE_CHARGE'))
					{
						throw new AccessDeniedException('Accès limité aux commerciaux.');
					}
					if ($project->getCommercialEnrollmentOpen())
					{
						if($project->getCommercialApplicants()->contains($user))
						{
							$this->flash->alert('Vous avez déjà postulé comme commercial sur cet appel à projet.');
						} else {
							$project->addCommercialApplicant($user);
							$this->notificator->notify(
								$project->getRbu(), 
								'{{ getUser('.$user->getId().')|linkUser }} a postulé en tant que commercial pour <a href="{{ url(\'je_business_project_show\', {id: '.$project->getId().'}) }}">'.$project.'</a>',
								'[Junior] '.$user.' a postulé en commercial pour '.$project,
								'Bonjour,<br /><br />

								{{ getUser('.$user->getId().')|linkUser }} a postulé en tant que commercial pour <a href="{{ url(\'je_business_project_show\', {id: '.$project->getId().'}) }}">'.$project.'</a>.'
							);
							$this->flash->success('Vous avez postulé comme commercial sur cet appel à projet.');
						}
					}
					else {
						$this->flash->error('Vous ne pouvez postuler en commercial sur cet appel à projet.');
					}
				break;
				case 'implementer':
					if ($project->getStudentsEnrollmentOpen())
					{
						if($project->getStudentsApplicants()->contains($user))
						{
							$this->flash->alert('Vous avez déjà postulé comme réalisateur sur cet appel à projet.');
						} else {
							$project->addStudentsApplicant($user);
							$this->notificator->notify(
								$project->getCommercial(), 
								'{{ getUser('.$user->getId().')|linkUser }} a postulé en tant que réalisateur pour <a href="{{ url(\'je_business_project_show\', {id: '.$project->getId().'}) }}">'.$project.'</a>',
								'[Junior] '.$user.' a postulé en réalisateur pour '.$project,
								'Bonjour,<br /><br />

								{{ getUser('.$user->getId().')|linkUser }} a postulé en tant que réalisateur pour <a href="{{ url(\'je_business_project_show\', {id: '.$project->getId().'}) }}">'.$project.'</a>.'
							);
							$this->flash->success('Vous avez postulé comme réalisateur sur cet appel à projet.');
						}
					}
					else {
						$this->flash->error('Vous ne pouvez postuler en réalisateur sur cet appel à projet.');
					}
				break;
				default:
					throw $this->createNotFoundException('URL incorrect');
				break;
			}
			$this->em->persist($project);
			$this->em->flush();
		}

		if (null !== $this->request->headers->get('referer'))
			return $this->redirect($this->request->headers->get('referer'));
		else
			return $this->redirect($this->generateUrl('je_business_my_project'));
	}

	public function choseCandidateAction(Project $project, $type, User $candidate, $accept)
	{
		if ($project->getState() !== Project::STATE_OPENED)
		{
			$this->flash->error('Vous ne pouvez lancer le recrutement sur cet appel à projet.');
		} else 
		{
			switch($type)
			{
				case 'commercial':
					if (!$this->security->isGranted('ROLE_ADMIN'))
					{
						throw new AccessDeniedException('Accès limité aux administrateurs.');
					}
					if (!$project->getCommercialEnrollmentOpen())
					{
						$this->flash->error('Le recrutement de commercial est fermé.');
					}
					else {
						if ($accept === 'accept')
						{
							if(null !== $project->getCommercial() && $project->getCommercial()->getId() == $candidate->getId())
							{
								$this->flash->alert($candidate.' a déjà été choisi comme commercial.');
							} else {
								$this->notificator->notify(
									$candidate, 
									'Vous avez été accepté en tant que commercial pour <a href="{{ url(\'je_business_project_show\', {id: '.$project->getId().'}) }}">'.$project.'</a>',
									'[Junior] Vous avez été accepté en tant que commercial pour '.$project,
									'Bonjour,<br /><br />

									Vous avez été accepté en tant que commercial pour <a href="{{ url(\'je_business_project_show\', {id: '.$project->getId().'}) }}">'.$project.'</a>.'
								);
								$project->setCommercial($candidate);
								$project->setCommercialEnrollmentOpen(false);
								//$project->getCommercialApplicants()->clear();
								$this->flash->success($candidate.' a été choisi comme commercial.');
							}
						} else {
							$project->removeCommercialApplicant($candidate);
							$this->flash->success($candidate.' a été refusé comme commercial.');
						}
					}
				break;
				case 'implementer':
					if (!$this->security->isGranted('ROLE_CHARGE'))
					{
						throw new AccessDeniedException('Accès limité aux commerciaux.');
					}
					if (!$project->getStudentsEnrollmentOpen())
					{
						$this->flash->error('Le recrutement de commercial est fermé.');
					}
					else {
						if ($accept === 'accept')
						{
							if($project->getStudents()->contains($candidate))
							{
								$this->flash->alert($candidate.' a déjà été choisi comme réalisateur.');
							} else {
								$this->notificator->notify(
									$candidate, 
									'Vous avez été accepté en tant que réalisateur pour <a href="{{ url(\'je_business_project_show\', {id: '.$project->getId().'}) }}">'.$project.'</a>',
									'[Junior] Vous avez été accepté en tant que réalisateur pour '.$project,
									'Bonjour,<br /><br />

									Vous avez été accepté en tant que réalisateur pour <a href="{{ url(\'je_business_project_show\', {id: '.$project->getId().'}) }}">'.$project.'</a>.'
								);
								$project->addStudent($candidate);
								$this->flash->success($candidate.' a été choisi comme réalisateur.');
							}
						} else {
							$project->removeStudentsApplicant($candidate);
							$this->flash->success($candidate.' a été refusé comme réalisateur.');
						}
					}
				break;
				default:
					throw $this->createNotFoundException('URL incorrect');
				break;
			}
			$this->em->persist($project);
			$this->em->flush();
		}

		if (null !== $this->request->headers->get('referer'))
			return $this->redirect($this->request->headers->get('referer'));
		else
			return $this->redirect($this->generateUrl('je_business_my_project'));
	}
}