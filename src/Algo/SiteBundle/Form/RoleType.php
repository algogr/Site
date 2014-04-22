<?php
// src/Algo/SiteBundle/Form/RoleType.php

namespace Algo\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RoleType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('description');
		
	}

	public function getName()
	{
		return 'role';
	}
}