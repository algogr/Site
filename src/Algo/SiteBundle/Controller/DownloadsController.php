<?php

// src/Algo/SiteBundle/Controller/DownloadsController.php

namespace Algo\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Algo\SiteBundle\Entity\Enquiry;
use Algo\SiteBundle\Form\EnquiryType;

class DownloadsController extends Controller
{

	public function indexAction()
	{
		$em = $this->getDoctrine()
		->getEntityManager();

		$blogs = $em->createQueryBuilder()
		->select('b')
		->from('AlgoSiteBundle:Blog',  'b')
		->addOrderBy('b.created', 'DESC')
		->getQuery()
		->getResult();

		return $this->render('AlgoSiteBundle:Page:index.html.twig', array(
				'blogs' => $blogs
		));
	}



	public function teamAction()
	{
		return $this->render('AlgoSiteBundle:Page:team.html.twig');
	}

	public function downloadsAction()
	{

		return $this->render('AlgoSiteBundle:Page:downloads.html.twig');

	}




	public function contactAction()
	{
		$enquiry = new Enquiry();
		$form = $this->createForm(new EnquiryType(), $enquiry);

		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);

			if ($form->isValid()) {
				$message = \Swift_Message::newInstance()
				->setSubject('Αίτηση επικοινωνίας με την Αλγόριθμος Πληροφορική')
				->setFrom('support@algo.gr')
				->setTo('support@algo.gr')
				->setBody($this->renderView('AlgoSiteBundle:Page:contactEmail.txt.twig', array('enquiry' => $enquiry)));
				$this->get('mailer')->send($message);

				$this->get('session')->setFlash('blogger-notice', 'Η αίτηση επικοινωνίας στάλθηκε επιτυχώς. Ευχαριστούμε!');

				// Redirect - This is important to prevent users re-posting
				// the form if they refresh the page
				return $this->redirect($this->generateUrl('AlgoSiteBundle_contact'));
			}
		}

		return $this->render('AlgoSiteBundle:Page:contact.html.twig', array(
				'form' => $form->createView()
		));
	}
}