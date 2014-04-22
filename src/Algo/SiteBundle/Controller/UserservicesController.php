<?php
// src/Algo/SiteBundle/Controller/UserrevicesController.php

namespace Algo\SiteBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Algo\SiteBundle\Entity\Document;
use Algo\SiteBundle\Form\DocumentType;
use Algo\SiteBundle\Entity\User;
use Algo\SiteBundle\Form\UserType;
use Algo\SiteBundle\Entity\Role;




class UserservicesController extends Controller
{
	public function filemanagerAction()
	{
		$s=$this->get('security.context');
		$user=$s->getToken()->getUser();
		$em = $this->getDoctrine()->getEntityManager();
		$s=$this->get('security.context');
		
		if ($s->isGranted('ROLE_ADMIN'))
			$docsset=$em->getRepository('AlgoSiteBundle:Document')->getAllDocs();
        else
			$docsset=$em->getRepository('AlgoSiteBundle:Document')->getDocsAllowed($user->getId(),$s);
		return $this->render('AlgoSiteBundle:Userservices:filemanager.html.twig',array(
			'docsset' => $docsset,
			'user' => $user,
		));
		
	}
	
	
	public function editdocAction($docpath)
	{
		$em = $this->getDoctrine()
		->getEntityManager();
		$document = $this->getDoctrine()->getRepository('AlgoSiteBundle:Document')
		->findOneByPath($docpath);
		
		if (!$document) {
			throw $this->createNotFoundException('Το αρχείο δεν βρέθηκε.');
		}
		$form = $this->createForm(new DocumentType(), $document);
		/*
		$document = $em->createQueryBuilder()
		->select('d')
		->from('AlgoSiteBundle:Document',  'd')
		->where ('d.path=:path')
		->setParameter("path",$docpath)
		->getQuery()
		->getResult();
		*/
		$request=$this->getRequest();
		
		if ($request->getMethod()=='POST')
		{
			$form->bindRequest($request);
			
			if ($form->isValid()) {
				
							
				if( $request->get('download') == 'Λήψη αρχείου' )
					return  $this->redirect($this->generateUrl('AlgoSiteBundle_doc_download',array('docpath' => $document->getPath())));
				if( $request->get('delete') == 'Διαγραφή Αρχείου' )
					return  $this->redirect($this->generateUrl('AlgoSiteBundle_doc_delete',array('docpath' => $document->getPath())));
					//return  $this->redirect($this->generateUrl('AlgoSiteBundle_team',array('docpath' => $document->getPath())));
				$em->flush($document);
				return $this->redirect($this->generateUrl('AlgoSiteBundle_filemanager'));
		}
		}
		
		return $this->render('AlgoSiteBundle:Userservices:editDocument.html.twig', array(
				'doc' => $document,
				'form'=> $form->createView()
		));
		
	

	}


	
	
	public function new_uploadAction()
	{
		$document=new Document();
		// $user=$this->get('security.context')->getToken()->getUser();
		//$em = $this->getDoctrine()->getEntityManager();
		
		//$users = $em->getRepository('AlgoSiteBundle:User');
		//$document->getUsersallowed()->add($users);
		$form = $this->createForm(new DocumentType(), $document);
		
		$request=$this->getRequest();
		
		if ($request->getMethod()=='POST')
		{
			$logger = $this->get('logger');
				
			
			$form->bindRequest($request);
			
			if ($form->isValid()) {
				
				$em = $this->getDoctrine()
				->getEntityManager();
			$user=$this->get('security.context')->getToken()->getUser();
			$s=$this->get('security.context');
			$document->setUploaderid($user->getId());
			
			if (!$s->isGranted('ROLE_ADMIN'))
			{
				$document->setPublic(false);
			}
			$em->persist($document);
			$em->flush($document);
			if (!$s->isGranted('ROLE_ADMIN'))
			{
				$send_from=$this->container->getParameter('registration_send_from');
				$send_to=$this->container->getParameter('admin_email');
				$message = \Swift_Message::newInstance()
				->setSubject('New upload to wwww.algo.gr')
				->setFrom($send_from)
				->setTo($send_to)
				->setBody($this->renderView('AlgoSiteBundle:Userservices:newUpload.txt.twig', 
						array('user' => $user->getUsername(),
								'path' => $document->getPath(),
								'shortdescr' => $document->getShortDescr())));
				$this->container->get('mailer')->send($message);
			}
			return $this->redirect($this->generateUrl('AlgoSiteBundle_upload'));
			
					}
		}
		return $this->render('AlgoSiteBundle:Userservices:newUpload.html.twig', array(
				'form' => $form->createView(),
				
				
				));
		
		
		
	}
	
	public function downloadAction($docpath)
	{
		$path = $this->get('kernel')->getRootDir(). "/../web/uploads/documents/";
		$file = $path.$docpath; // Path to the file on the server
		$request = $this->get('request');
		$content = file_get_contents($file);
		
		$response = new Response();
		
		//set headers
		$response->headers->set('Content-Type', 'mime/type');
		$response->headers->set('Content-Disposition', 'attachment;filename="'.$file);
		
		$response->setContent($content);
		return $response;
	}
	
	
	public function deleteAction($docpath)
	{
		$document = $this->getDoctrine()->getRepository('AlgoSiteBundle:Document')
		->findOneByPath($docpath);
		
		$em = $this->getDoctrine()->getEntityManager();
		$em->remove($document);
		$em->flush();
		return $this->redirect($this->generateUrl('AlgoSiteBundle_filemanager'));
	}
	

	


}
