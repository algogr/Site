<?php
// src/Algo/SiteBundle/Entity/Repository/DocumentRepository.php

namespace Algo\SiteBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;


class DocumentRepository extends EntityRepository
{
	

	public function getPublicDocs()
	{
		$qb = $this->createQueryBuilder('d')
		->select('d')
		->where('d.public = 1');
		
		
		return $qb->getQuery()->getResult();
	}
	
	public function getUserUploadedDocs($userid)
	{
		$qb = $this->createQueryBuilder('d')
		->select('d')
		->where('d.uploaderid = :user_id')
		->setParameter('user_id', $userid);
		
		return $qb->getQuery()->getResult();
	
	}
	
	
	
	public function getUserAllowedDocs($userid)
	{
		$query=$this->createQueryBuilder('d')
		->select ('d')
		->leftJoin('d.usersallowed','j')
		->where('j.id = :usersallowedId')  
		->setParameter("usersallowedId", $userid);
		
		return $query->getQuery()->getResult();
	}
	
	public function getUserRoleDocs($s)
	{
		//$s=$this->container->get('security.context');
		//$docs=$em->createQueryBuilder()->select('d','r.description')->from('AlgoSiteBundle:Document','d')
		//->leftJoin("AlgoSiteBundle:Role", "r", "WITH", "d.role=r.id")
		//->getQuery()->getResult();
		$query=$this->createQueryBuilder('d')->select('d');
		
		$docs=$query->getQuery()->getResult();
				
		$docsrole=array();
		foreach ($docs as $doc)
		{
			if ($rd=$doc->getRole())
			{
				if ($s->isGranted($rd->getDescription()))
				{
					array_push($docsrole, $doc);
				}
			}
		}
		return $docsrole;
	}
	
	public function getDocsAllowed($userid,$s)
	{
		$docsset=array_unique((array) array_merge((array)$this->getPublicDocs(),
				(array)$this->getUserAllowedDocs($userid),(array)$this->getUserRoleDocs($s),
				(array)$this->getUserUploadedDocs($userid)));
		
		return $docsset;

	}
	
	public function getAllDocs()
	{
		$query=$this->createQueryBuilder('d')->select('d');
		$docsall=$query->getQuery()->getResult();
		return $docsall;
		
	}

	

}