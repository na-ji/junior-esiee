<?php

namespace JuniorEsiee\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JuniorEsiee\PageBundle\Form\OffreFormType;
use JuniorEsiee\PageBundle\Form\ContactFormType;

class PageController extends Controller
{
    function contactAction()
    {
        $form = $this->createForm(new  ContactFormType);
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
        return $this->render('JuniorEsieePageBundle:Page:appeloffre.html.twig', array('form' => $form->createView(),));
    }
}