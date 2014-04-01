<?php
// src/Algo/SiteBundle/Controller/ProfileController.php;
namespace Algo\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Entities\User;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\SecurityController as BaseController;
class ProfileController extends BaseController
{

	public function showAction()
	{
		$user = $this->container->get('security.context')->getToken()->getUser();
		//$logger=$this->get('logger');
		//$logger->info('jim instanceof'. get_class($user));
		if (!is_object($user) || !$user instanceof UserInterface) {
			throw new AccessDeniedException('This user does not have access to this section.');
		}
	
		return $this->container->get('templating')->renderResponse('FOSUserBundle:Profile:show.html.'.$this->container->getParameter('fos_user.template.engine'), array('user' => $user));
	}
	
}