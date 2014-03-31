<?php
// src/Algo/SiteBundle/Controller/RegistrationController.php;
namespace Algo\SiteBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Controller\RegistrationController as BaseController;

class RegistrationController extends BaseController
{
	public function registerAction(Request $request)
	{
		/** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
		$formFactory = $this->container->get('fos_user.registration.form.factory');
		/** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
		$userManager = $this->container->get('fos_user.user_manager');
		/** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
		$dispatcher = $this->container->get('event_dispatcher');
	
		$user = $userManager->createUser();
		
	
		$event = new GetResponseUserEvent($user, $request);
		$dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);
	
		if (null !== $event->getResponse()) {
			return $event->getResponse();
		}
	
		$form = $formFactory->createForm();
		$form->setData($user);
		
		
		if ($request->getMethod()=='POST') {
			
			$form->bind($request);
			if ($form->isValid()) {
				
				$event = new FormEvent($form, $request);
				$dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
				$user->setEnabled(false);
				$userManager->updateUser($user);
	            /**************************************
	            *******My functionality send email*****
	            ***************************************/
				$send_to=$this->container->getParameter('registration_send_to');
				$send_from=$this->container->getParameter('registration_send_from');
				$message = \Swift_Message::newInstance()
				->setSubject('Αίτηση εγγραφής στο www.algo.gr')
				->setFrom($send_from)
				->setTo($user->getEmail())
				->setBody($this->container->get('templating')->render('FOSUserBundle:Registration:email.txt.twig',array('user'=>$user, 'confirmationUrl'=> 'www.algo.gr')));
				$this->container->get('mailer')->send($message);
					
				$message1 = \Swift_Message::newInstance()
				->setSubject('Νέα αίτηση εγγραφής στο www.algo.gr')
				->setFrom($send_from)
				->setTo($send_to)
				->setBody($this->container->get('templating')->render('FOSUserBundle:Registration:emailalgo.txt.twig',array('user'=>$user, 'confirmationUrl'=> 'www.algo.gr')));
				$this->container->get('mailer')->send($message1);
				
	            /***************************
	             ***End of my functionality*				
				***************************/
				
				
				if (null === $response = $event->getResponse()) {
					$url = $this->container->get('router')->generate('fos_user_registration_confirmed');
					$response = new RedirectResponse($url);
				}
	
				$dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));
	
				return $response;
			}
		}
	
		return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register.html.'.$this->getEngine(), array(
				'form' => $form->createView(),
		));
	}
	
	
		
	
}
	
