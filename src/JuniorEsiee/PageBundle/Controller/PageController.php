<?php

namespace JuniorEsiee\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    function contactAction()
    {
        return $this->render('JuniorEsieePageBundle:Page:contact.html.twig');
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
        return $this->render('JuniorEsieePageBundle:Page:appeloffre.html.twig');
    }
}