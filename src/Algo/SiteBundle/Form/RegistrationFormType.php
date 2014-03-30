<?php
// src/Algo/SiteBundle/Form/RegistrationFormType
namespace Algo\SiteBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		parent::buildForm($builder, $options);

		// add your custom field
		$builder->add('name')
		->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'Κωδικός πρόσβασης'),
                'second_options' => array('label' => 'Επιβεβαίωση κωδικού'),
                'invalid_message' => 'fos_user.password.mismatch',
            ));
	}

	 public function getName()
	  {
		  return 'algo_user_registration';
	  }
}