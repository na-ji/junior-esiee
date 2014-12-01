<?php

namespace JuniorEsiee\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JuniorEsiee\PageBundle\Form\OffreFormType;
use JuniorEsiee\PageBundle\Form\ContactFormType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JuniorEsiee\BusinessBundle\Entity\Project;
use Application\Sonata\MediaBundle\Entity\Media;

class PageController extends Controller
{
    function contactAction()
    {
        $form = $this->createForm(new  ContactFormType);
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->handleRequest($request);

			if ($form->isValid()) {
				// Perform some action, such as sending an email
				$data = $form->getData();
				
				$messageadmin = \Swift_Message::newInstance()
					->setSubject("Message de CONTACT depuis de le site web de ".$data['common']['prenom']." ".$data['common']['nom'].".")
					->setFrom(array($data['common']['email'] => $data['common']['nom'].' '.$data['common']['prenom']))
					->setTo('contact@junioresiee.com')
					->setContentType('text/html')
					->setBody($this->renderView('JuniorEsieePageBundle:Email:EmailContact.html.twig', array(
						'data' => $data
					)))
				;
				
				
				$response = $this->get('mailer')->send($messageadmin);

				$messageclient = \Swift_Message::newInstance()
					->setSubject("Confirmation d'envoi d'appel d'offre.")
					->setFrom(array('contact@junioresiee.com' => "Site de Junior ESIEE"))
					->setTo($data['common']['email'])
					->setContentType('text/html')
					->attach(\Swift_Attachment::fromPath('uploads/plaquetteclient.pdf'))
					->attach(\Swift_Attachment::fromPath('uploads/Conseil-administration-2014.pdf'))	
					->setBody($this->renderView('JuniorEsieePageBundle:Email:EmailConfirmation.html.twig', array(
						'data' => $data, 
						'cas' => " demande de contact"
					)))
				;

				$this->get('mailer')->send($messageclient);
				
				
				$this->get('session')->getFlashBag()->add('success', '<strong>Votre demande de contact a bien été envoyée à nos équipes</strong>, un mail de confirmation vous a été envoyé. <strong>Merci</strong> de votre intérêt.');
				
				// Redirect - This is important to prevent users re-posting
				// the form if they refresh the page
				return $this->redirect($this->generateUrl('page_accueil'));
			}

		}
		return $this->render('JuniorEsieePageBundle:Page:contact.html.twig', array('form' => $form->createView(),)); 
	}	
		
	function indexAction()
	{
		return $this->render('JuniorEsieePageBundle:Page:index.html.twig');
	}
	
	function aboutAction()
	{
		return $this->render('JuniorEsieePageBundle:Page:about.html.twig');
	}
	
	function prestationsAction()
	{
		return $this->render('JuniorEsieePageBundle:Page:prestations.html.twig');
	}
	
	function appeloffreAction()
	{
		$form    = $this->createForm(new OffreFormType);
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->handleRequest($request);

			if ($form->isValid()) {
				// Perform some action, such as sending an email
				$data = $form->getData();
				
				$messageadmin = \Swift_Message::newInstance()
					->setSubject("Message d'APPEL D'OFFRE depuis de le site web de ".$data['common']['prenom']." ".$data['common']['nom'].".")
					->setFrom(array($data['common']['email'] => $data['common']['nom'].' '.$data['common']['prenom']))
					->setTo('contact@junioresiee.com')
					->setContentType('text/html')
				;

				/* On sauvegarde dans la BDD l'appel d'offre ---> Il sera affiché dans la boite de réception */
				// On créer un objet et rempli les infos
				$project = new Project;
				$project
					->setClientLastName($data['common']['nom'])
					->setClientFirstName($data['common']['prenom'])
					->setClientCompany($data['common']['societe'])
					->setClientPhone($data['common']['telephone'])
					->setClientEmail($data['common']['email'])
					->setClientAddress($data['adresse'])
					->setClientZipCode($data['codepostal'])
					->setClientCity($data['ville'])
					->setDescription($data['description'])
				;
				// On récupère l'Entity Manager
				$em           = $this->get('doctrine.orm.entity_manager');
				$mediaManager = $this->get('sonata.media.manager.media');
				
				if (null !== $form['cahiercharges']->getData()) {
					$file1 = $form['cahiercharges']->getData();
					
					$ext1  = $file1->guessExtension();
					$file1 = $file1->move('tmp', 'cahier_des_charges.'.$ext1);
					$messageadmin->attach(\Swift_Attachment::fromPath('tmp/cahier_des_charges.'.$ext1));

					// On sauvegarde le media et on l'attache à l'appel à projet
					$media1 = new Media;
					$media1->setContext('scopeStatement');
					$media1->setProviderName('sonata.media.provider.file');
					$media1->setBinaryContent($file1);
					$media1->setName('Cahier des charges de '.$project->getClientCompany());
					$mediaManager->save($media1);

					$project->setScopeStatement($media1);
				}
				
				if (null !== $form['chartegraph']->getData()) {
					$file2 = $form['chartegraph']->getData();
					
					$ext2  = $file2->guessExtension();
					$file2 = $file2->move('tmp', 'charte_graphique.'.$ext2);
					$messageadmin->attach(\Swift_Attachment::fromPath('tmp/charte_graphique.'.$ext2));

					// On sauvegarde le media et on l'attache à l'appel à projet
					$media2 = new Media;
					$media2->setContext('graphicCharter');
					$media2->setProviderName('sonata.media.provider.file');
					$media2->setBinaryContent($file2);
					$media2->setName('Charte graphique de '.$project->getClientCompany());
					$mediaManager->save($media2);

					$project->setGraphicCharter($media2);
				}

				// On sauvegarde l'appel à projet
				$em->persist($project);
				$em->flush();
				
				$messageadmin->setBody($this->renderView('JuniorEsieePageBundle:Email:EmailOffre.html.twig', array(
					'data'    => $data,
					'project' => $project,
				)));

				$this->get('mailer')->send($messageadmin);
				
				$messageclient = \Swift_Message::newInstance()
					->setSubject("Confirmation d'envoi d'appel d'offre.")
					->setFrom(array('contact@junioresiee.com' => "Site de Junior ESIEE"))
					->setTo($data['common']['email'])
					->setContentType('text/html')
					->attach(\Swift_Attachment::fromPath('uploads/plaquetteclient.pdf'))
					->attach(\Swift_Attachment::fromPath('uploads/Conseil-administration-2014.pdf'))	
					->setBody($this->renderView('JuniorEsieePageBundle:Email:EmailConfirmation.html.twig', array(
						'data' => $data, 
						'cas'  => " dépot d'appel d'offre"
					)))
				;

				$this->get('mailer')->send($messageclient);				
				
				$this->get('session')->getFlashBag()->add('success', "<strong>Votre appel d'offre a bien été envoyé à nos équipes</strong>, un mail de confirmation vous a été envoyé. <strong>Merci</strong> de votre intérêt.");
				
				// Redirect - This is important to prevent users re-posting
				// the form if they refresh the page
				return $this->redirect($this->generateUrl('page_accueil'));
			}

		}

		return $this->render('JuniorEsieePageBundle:Page:appeloffre.html.twig', array('form' => $form->createView(),));
	}
	
	function mentionsAction()
	{
		return $this->render('JuniorEsieePageBundle:Annexe:mentions.html.twig');
	}
	
	function presseAction()
	{
		return $this->render('JuniorEsieePageBundle:Annexe:presse.html.twig');
	}

	/**
	 * @Template
	 */
	public function toolIndexAction()
	{
		return array();
	}
}
