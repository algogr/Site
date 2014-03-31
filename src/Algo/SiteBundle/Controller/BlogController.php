<?php
// src/Algo/SiteBundle/Controller/BlogController.php

namespace Algo\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Algo\SiteBundle\Entity\Blog;
use Doctrine\DBAL\Types\BlobType;
use Algo\SiteBundle\Form\BlogType;

/**
 * Blog controller.
 */
class BlogController extends Controller
{
	/**
	 * Show a blog entry
	 */
	public function showAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();

		$blog = $em->getRepository('AlgoSiteBundle:Blog')->find($id);
		
		

		if (!$blog) {
			throw $this->createNotFoundException('Η ανάρτηση δεν βρέθηκε.');
		}

		return $this->render('AlgoSiteBundle:Blog:show.html.twig', array(
				'blog'      => $blog,
				
		));
		/////////////////////////////
		$entities          = $this->getDoctrine()->getRepository('CliniqueGynecoBundle:Article')->createQueryBuilder('p')->setFirstResult(($page * $articles_per_page) - $articles_per_page)->setMaxResults($this->container->getParameter('max_articles_on_listepage'))->getQuery()->getResult();
		
		
		
		
		
	}
	
	public function editAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
	
		$blog = $em->getRepository('AlgoSiteBundle:Blog')->find($id);
		$blog->createBrief();
	
		if (!$blog) {
			throw $this->createNotFoundException('Η ανάρτηση δεν βρέθηκε.');
		}
		
		$form = $this->createForm(new BlogType(), $blog);
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
		
			if ($form->isValid()) {
				$em = $this->getDoctrine()
				->getEntityManager();
				
				$blog->upload();
				

				// $blog = $em->getRepository('AlgoSiteBundle:Blog')->find($id);
				$em->flush();
				// Perform some action, such as sending an email
		
				// Redirect - This is important to prevent users re-posting
				// the form if they refresh the page
				return $this->redirect($this->generateUrl('AlgoSiteBundle_homepage'));
			}
		}
		
		return $this->render('AlgoSiteBundle:Blog:edit.html.twig', array(
				'form' => $form->createView(),
				'blog'      => $blog,
	
		));
	
	}
	
	
	public function deleteAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
	
		$blog = $em->getRepository('AlgoSiteBundle:Blog')->find($id);
	
	
		if (!$blog) {
			throw $this->createNotFoundException('Η ανάρτηση δεν βρέθηκε.');
		}
		$em->remove($blog);
		$em->flush();
		
		return $this->redirect($this->generateUrl('AlgoSiteBundle_homepage'));
		
	
	}
	
	
	
	
	
	
	
	public function newAction()
	{
		$blog = new Blog();
		$form = $this->createForm(new BlogType(), $blog);
		
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
		
			if ($form->isValid()) {
				$em = $this->getDoctrine()
				->getEntityManager();
				$blog->upload();
				$blog->createBrief();
				$em->persist($blog);
				$em->flush();
				// Perform some action, such as sending an email
		
				// Redirect - This is important to prevent users re-posting
				// the form if they refresh the page
				return $this->redirect($this->generateUrl('AlgoSiteBundle_blog_new'));
			}
		}
		
		return $this->render('AlgoSiteBundle:Blog:new.html.twig', array(
				'form' => $form->createView()
		));
	}

}