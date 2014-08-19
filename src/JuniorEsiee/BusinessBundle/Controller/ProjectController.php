<?php

namespace JuniorEsiee\BusinessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProjectController extends Controller
{
	public function indexAction()
	{
		return $this->render('JuniorEsieeBusinessBundle:Project:index.html.twig');
	}
}