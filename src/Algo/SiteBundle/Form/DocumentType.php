<?php

// src/Algo/SiteBundle/Form/FileType.php

namespace Algo\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core;
use Algo\SiteBundle\Entity\User;
use Algo\SiteBundle\Form\UserType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class DocumentType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		//$user=$options['user'];
		
		
		// $builder->add('id');
		//$builder->add('filename');
		$builder->add('shortdescr');    
		$builder->add('descr');
		// $builder->add('path');
		
		
		$builder->add('role','entity',array(
				'class' => 'Algo\SiteBundle\Entity\Role',
				'property' => 'description',
				'empty_value' => 'Choose a role'
				
				
		));
		$builder->add('public','checkbox',array('required' => false));
		// $builder->add('usersallowed', 'collection', array('type' => new UserType()));
		$builder->add('usersallowed','entity',array(
                'class' => 'Algo\SiteBundle\Entity\User',
                'property' => 'username',
                'multiple' => true
                
              ));
		$builder->add('file','file');
	
    }

	public function getName()
	{
		return 'document';
	}
	
	/*
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
	
				'user' => null,
				
		));
	}
	*/
}