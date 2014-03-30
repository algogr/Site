<?php
// src/Algo/SiteBundle/Form/UserType.php

namespace Algo\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name');
		$builder->add('username');
		$builder->add('enabled');
		$builder->add('approved');
		$builder->add('email', 'email');
		$builder->add('rolelist', 'choice', array(
		'choices'   => array(
    						'ROLE_ADMIN'   => 'ROLE_ADMIN',
    						'ROLE_USER' => 'ROLE_USER',
								),
		'property_path' => false,
				
				
		// 'data' => array('ROLE_USER'=>'ROLE_USER',),
		'multiple'  => true)
		);
		
	}

	public function getName()
	{
		return 'user';
	}
}