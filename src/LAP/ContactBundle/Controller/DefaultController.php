<?php

namespace LAP\ContactBundle\Controller;

use LAP\ContactBundle\Entity\Contact;
use LAP\ContactBundle\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends Controller
{
	/**
	* @Security("has_role('ROLE_ADMIN')")
	*/
    public function indexAction(Request $request)
    {
		$contact = new Contact();
		
		$form = $this->createForm(ContactType::class, $contact);
		
		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			/* Email sending */
			$nom = $form["nom"]->getData();
			$prenom = $form["prenom"]->getData();
			$email = $form["email"]->getData();
			$telephone = $form["telephone"]->getData();
			$choix = $form["choix"]->getData();
			$texte = $form["texte"]->getData();
			
			$name = 'Jessie';
			$message = \Swift_Message::newInstance()
				->setSubject('Vous avez un nouveau message')
				->setFrom('contact@ecoledesapprentissages.fr')
				->setTo('contact@ecoledesapprentissages.fr')
				->setBody(
					$this->renderView(
						'Email/registration.html.twig',
						array( 'name' => $name, 'nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'telephone' => $telephone, 'choix' => $choix, 'texte' => $texte )
					),
					'text/html',
					'iso-8859-2'
				)
			;
			$this->get('mailer')->send($message);
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($contact);
			$em->flush();
			
			$request->getSession()->getFlashBag()->add('notice', 'Votre message a bien été envoyé. Nous vous recontacterons dans les meilleurs délais.');
			
			return $this->redirectToRoute('lap_contact_homepage');
		}
		
		return $this->render('LAPContactBundle:Default:index.html.twig', array(
			'form' => $form->createView(),
		));
    }
}
