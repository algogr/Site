<?php
// src/Algo/SiteBundle/Controller/UsermanagementController.php

namespace Algo\SiteBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Algo\SiteBundle\Entity\User;
use Algo\SiteBundle\Form\UserType;

ob_start();

class UsermanagementController extends Controller
{

	public function indexAction()
	{
			
				
		$em = $this->getDoctrine()
		->getEntityManager();
			
		$users_full = $em->createQueryBuilder()
		->select('u')
		->from('AlgoSiteBundle:User',  'u')
		->addOrderBy('u.username', 'DESC')
		->getQuery()
		->getResult();


		return $this->render('AlgoSiteBundle:Security:userindex.html.twig', array(
				'users' => $users_full,
				
					
		));
	}

	public function editAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		
		$user = $em->getRepository('AlgoSiteBundle:User')->find($id);
		
		
		if (!$user) {
			throw $this->createNotFoundException('Ο χρήστης δεν βρέθηκε.');
		}
		
		$form = $this->createForm(new UserType(), $user);
		
		
		
		
		$request = $this->getRequest();
		if ($request->getmethod() == 'GET')
		{
			
			$roles=$user->getRoles();
			//$roles1=$form['rolelist']->setData($roles);
			$logger = $this->get('logger');
			//$logger->info ('logger-roles:'.$roles1[0]);
			/*
			foreach ($roles as $role)
			{
				//array_push($form('rolelist')->data,$role);
				$form['rolelist']->setData($role);
			}
			*/
			//$form->get('rolelist')->setData($roles);
		}
		
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			$roles=$user->getRoles();
			
			foreach ($roles as $role)
				$user->removeRole($role);
			//$roles = $form->get('rolelist')->getData();
			$roles = $form['rolelist']->getData();
			$logger = $this->get('logger');
			
			//$logger->error('An error occurred');
			
			foreach($roles as $key => $value)
			{
				$logger->info ('I just got the logger:'.$value);
				
				$user->addRole($value);
				
			}
			
			/**
			foreach ($roles as $selectedOption)
				
			{
				if ($selectedOption=='ROLE_USER')
				{
						$logger->info ('I just got the logger epitelous:'.$selectedOption);
						$user->addRole('ROLE_USER');
				}
				
				//$user->addRole($selectedOption);
			
			}
			**/
			//$user->addRole('ROLE_USER');
		
			if ($form->isValid()) {
				//$logger->info ('I just got the logger:'.$selectedOption);
				$em = $this->getDoctrine()
				->getEntityManager();
				// $blog = $em->getRepository('AlgoSiteBundle:Blog')->find($id);
				
				$em->flush();
				// Perform some action, such as sending an email
		
				// Redirect - This is important to prevent users re-posting
				// the form if they refresh the page
				return $this->redirect($this->generateUrl('AlgoSiteBundle_user_management'));
			}
		}
		
		return $this->render('AlgoSiteBundle:Security:useredit.html.twig', array(
				'form' => $form->createView(),
				'user' => $user,
				'roles'=> $user->getRoles(),
		
		));
		
		
	

	}




}