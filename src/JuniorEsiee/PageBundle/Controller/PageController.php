<?php

namespace JuniorEsiee\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JuniorEsiee\PageBundle\Form\OffreFormType;
use JuniorEsiee\PageBundle\Form\ContactFormType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
				$data = $form -> getData();
				
				$messageadmin = \Swift_Message::newInstance()
				->setSubject('Message de CONTACT depuis de le site web de $data['common']['prénom'] $data['common']['nom']')
				->setFrom($data['common']['email'])
				->setTo('contact@junioresiee.com')
				->setContentType('text/html')
				->setBody($this->renderView('JuniorEsieePageBundle:Email:EmailContact.html.twig', array('data' => $data)));
				
				
				$response = $this->get('mailer')->send($messageadmin);

				$messageclient = \Swift_Message::newInstance()
				->setSubject("Confirmation d'envoi d'appel d'offre.")
				->setFrom('Junior ESIEE website')
				->setTo($data['common']['email'])
				->setContentType('text/html')
				->attach(\Swift_Attachment::fromPath('uploads/plaquetteclient.pdf'))
				->attach(\Swift_Attachment::fromPath('uploads/Conseil-administration-2014.pdf'))	
				->setBody($this->renderView('JuniorEsieePageBundle:Email:EmailConfirmation.html.twig', array('data' => $data, 'cas' => "votre demande de contact")));

				$this->get('mailer')->send($messageclient);
				
				
				$this->get('session')->getFlashBag()->add('success', '<strong>Votre demande de contact a bien été envoyé à nos équipes</strong>, un mail de confirmation vous a été envoyé. <strong>Merci</strong> de votre intérêt.');
				
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
		$form = $this->createForm(new OffreFormType);
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->handleRequest($request);

			if ($form->isValid()) {
				// Perform some action, such as sending an email
				$data = $form -> getData();
				

				
				 $messageadmin = \Swift_Message::newInstance()
				->setSubject("Message d'APPEL D'OFFRE depuis de le site web de $data['common']['prénom'] $data['common']['nom']. ")
				->setFrom($data['common']['email'])
				->setTo('contact@junioresiee.com')
				->setContentType('text/html');
				
				if ( $form['cahiercharges']->getData() != null) {
					$file1 = $form['cahiercharges']->getData();
					
					$ext1  = $file1->guessExtension();
					$file1->move('tmp', 'cahier_charges.'.$ext1);
					$messageadmin->attach(\Swift_Attachment::fromPath('tmp/cahier_charges.'.$ext1));
				}
				
				if ( $form['chartegraph']->getData() != null) {
					$file2 = $form['chartegraph']->getData();
					
					$ext2  = $file2->guessExtension();
					$file2->move('tmp', 'charte_graphique.'.$ext2);
					$messageadmin->attach(\Swift_Attachment::fromPath('tmp/charte_graphique.'.$ext2));
				}
				
				$messageadmin->setBody($this->renderView('JuniorEsieePageBundle:Email:EmailOffre.html.twig', array('data' => $data)));

				$this->get('mailer')->send($messageadmin);
				
				 $messageclient = \Swift_Message::newInstance()
				->setSubject("Confirmation d'envoi d'appel d'offre.")
				->setFrom('Junior ESIEE website')
				->setTo($data['common']['email'])
				->setContentType('text/html')
				->attach(\Swift_Attachment::fromPath('uploads/plaquetteclient.pdf'))
				->attach(\Swift_Attachment::fromPath('uploads/Conseil-administration-2014.pdf'))	
				->setBody($this->renderView('JuniorEsieePageBundle:Email:EmailConfirmation.html.twig', array('data' => $data, 'cas' => "votre dépot d'appel d'offre")));

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
}
