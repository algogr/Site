<?php
// src/Algo/SiteBundle/Controller/SecurityController.php;
namespace Algo\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
//use Symfony\Component\HttpFoundation\RedirectResponse;
//use FOS\UserBundle\Controller\SecurityController as Base//Controller;
class SecurityController extends Controller
{
	
	public function loginAction(\Symfony\Component\HttpFoundation\Request $request1)
	{ 
		//$request = parent::loginAction($request1);
		$request = $this->getRequest();
		$session = $request->getSession();

		// get the login error if there is one
		if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
				$error = $request->attributes->get(
				SecurityContext::AUTHENTICATION_ERROR);
			}
		 else {
				$error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
				$session->remove(SecurityContext::AUTHENTICATION_ERROR);
		}
		$csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authentication');

		return $this->render(
				'FOSUserBundle:Security:login.html.twig',
				array(
						///last username entered by the user
						'last_username' => $session->get(SecurityContext::LAST_USERNAME),
						'error'         => $error,
						'csrf_token'    => $csrfToken
				)
		);
	}
	/*
	 * 
	 public function loginAction(\Symfony\Component\HttpFoundation\Request $request)
	{
		$response = parent::loginAction($request);
		
		//do something else;
		
		return $response;
		
	}
	*/
}