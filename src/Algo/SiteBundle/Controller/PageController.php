<?php
// src/Algo/SiteBundle/Controller/PageController.php

namespace Algo\SiteBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Algo\SiteBundle\Entity\Enquiry;
use Algo\SiteBundle\Form\EnquiryType;

class PageController extends Controller
{
	
	public function indexAction($page)
	{
			
			
			$articles_per_page = $this->container->getParameter('max_articles_on_listpage');
			
			
			$em = $this->getDoctrine()
			->getEntityManager();
			
			$blogs_full = $em->createQueryBuilder()
			->select('b')
			->from('AlgoSiteBundle:Blog',  'b')
			->addOrderBy('b.created', 'DESC')
			->getQuery()
			->getResult();
				
		
			$blogs = $em->createQueryBuilder()
			->select('b')
			->from('AlgoSiteBundle:Blog',  'b')
			->addOrderBy('b.created', 'DESC')
			->setFirstResult( $page )
			->setMaxResults( $articles_per_page)
			->getQuery()
			->getResult();
			
			$total_articles    = count($blogs_full);
			$last_page         = ceil($total_articles / $articles_per_page)-1;
			$current_page= $page;
			$previous_page     = $current_page -1;
			$next_page         = $current_page +1 ;
			//$previous_page=-1;
			//$next_page=1;
			
			
		
			return $this->render('AlgoSiteBundle:Page:index.html.twig', array(
					'blogs' => $blogs,
					'previous_page' => $previous_page,
					'current_page' => $current_page,
					'next_page' => $next_page,
					'last_page' => $last_page,
					
			));
	}
	
	
	
	public function teamAction()
	{
		return $this->render('AlgoSiteBundle:Page:team.html.twig');
	}
	
	
	public function downloadslistAction()
	{
	
		return $this->render('AlgoSiteBundle:Page:downloadslist.html.twig');

	}

	
	
	
	public function contactAction()
	{
		$enquiry = new Enquiry();
		$form = $this->createForm(new EnquiryType(), $enquiry);
	
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
	
			if ($form->isValid()) {
				$message = \Swift_Message::newInstance()
				->setSubject('Αίτηση επικοινωνίας με την Αλγόριθμος Πληροφορική')
				->setFrom('support@algo.gr')
				->setTo('support@algo.gr')
				->setBody($this->renderView('AlgoSiteBundle:Page:contactEmail.txt.twig', array('enquiry' => $enquiry)));
				$this->get('mailer')->send($message);
				
				$this->get('session')->setFlash('blogger-notice', 'Η αίτηση επικοινωνίας στάλθηκε επιτυχώς. Ευχαριστούμε!');
				
				// Redirect - This is important to prevent users re-posting
				// the form if they refresh the page
				return $this->redirect($this->generateUrl('AlgoSiteBundle_contact'));
				}
		}
	
		return $this->render('AlgoSiteBundle:Page:contact.html.twig', array(
				'form' => $form->createView()
		));
	}
	
	public function dojotestAction()
	{
		return $this->render('AlgoSiteBundle:Page:dojotest.html.twig');
	}
}