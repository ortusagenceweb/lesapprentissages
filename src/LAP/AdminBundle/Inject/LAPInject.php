<?php

namespace LAP\AdminBundle\Inject;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;

class LAPInject extends Controller
{
	/**
	*
	* Inject datas for all the functions
	*
	*/
	protected $em;
	
	public function __construct(\Doctrine\ORM\EntityManager $em)
	{
		$this->em = $em;
	}
	
	public function connectedUserdatas($u)
	{
		$name = $u->getName();
		$surname = $u->getSurname();
		$picture = $u->getPicture();
		$id = $u->getId();
		$datas = array(
			'name' => $name,
			'surname' => $surname,
			'picture' => $picture,
			'id' => $id
		);
		return $datas;
	}
	
	private function listeLastmessagerie($id)
	{
		/* 5 last messages in Messagerie */
		$repository = $this->em->getRepository('LAPMessagerieBundle:Messagerie');
		$listeLastmessagerie = $repository->findLastmessages($id, 5);
		return $listeLastmessagerie;
	}
	
	private function listeLastnotifications($limit)
	{
		/* $limit last notifications */
		$repository = $this->em->getRepository('LAPNotificationsBundle:Notifications');
		$listeLastnotifications = $repository->findLastnotifications($limit);
		return $listeLastnotifications;
	}
	
	public function listeAllnotifications()
	{
		/* All notifications */
		$repository = $this->em->getRepository('LAPNotificationsBundle:Notifications');
		$listeAllnotifications = $repository->findAllnotifications();
		return $listeAllnotifications;
	}
	
	public function lastCalendarid()
	{
		$repository = $this->em->getRepository('LAPCalendarBundle:Events');
		$lastCalendarid = $repository->findLastcalendarid();
		return $lastCalendarid;
	}

	public function miniListes($usrid)
    {
        $liste = array();
        /* 5 (max) last messages in Messagerie */
        $listeLastmessagerie = $this->listeLastmessagerie( $usrid );

        /* 5 (max) last notifications */
        $listeLast5notifs = $this->listeLastnotifications(5);

        /* 5 (max) last tasks */
        $repository = $this->em->getRepository('LAPTodoBundle:Todolist');
        $list5LastTasks = $repository->findAlltasks( $usrid, 5 );

        $liste[] = $listeLastmessagerie;
        $liste[] = $listeLast5notifs;
        $liste[] = $list5LastTasks;

        return $liste;
    }

    /*
     * Setting active value to 1 (true) directly from admin homepage
     */
    public function modifArticle($request)
    {
        //$em = $this->getDoctrine()->getManager();
        $article = $this->em->getRepository('LAPBlogBundle:Article')->find( $request->query->get('id') );
        if( $request->query->get('act') == "active" ) { $article->setActive(1); }
        elseif( $request->query->get('act') == "unactive" ) { $article->setActive(0); }
        $this->em->flush();

        $request->getSession()->getFlashBag()->add('notice', 'Votre article a bien été modifié.');
    }

    /*
     * Limit number of characters in title to 80
     */
    public function limit80($listeLastmessages, $t)
    {
        foreach($listeLastmessages as $key => $contact)
        {
            $txt = $listeLastmessages[$key]->getTexte();
            $txtcuted = $t->cuttext($txt);
            $listeLastmessages[$key]->setTexte( trim(strip_tags(substr($txtcuted, 0, 80))) );
        }
        return $listeLastmessages;
    }

    /*
     * Limit number of characters in title to 45
     */
    public function limit45($listeLastmessagerie, $t)
    {
        foreach($listeLastmessagerie as $key => $contact)
        {
            $txt = $listeLastmessagerie[$key]['titre'];
            $txtcuted = $t->cuttext($txt);
            $listeLastmessagerie[$key]['titre'] = ( trim(strip_tags(substr($txtcuted, 0, 45))) );
        }
        return $listeLastmessagerie;
    }

    /*
     * Formatting text for viewing in admin homepage table
     */
    public function formatTable($listeArticles, $t)
    {
        foreach($listeArticles as $key => $article)
        {
            $txt = $listeArticles[$key]->getTexte();
            $txtcuted = $t->cuttext($txt);
            $listeArticles[$key]->setTexte( trim(strip_tags(substr($txtcuted, 0, 80))) );
        }
        return $listeArticles;
    }

    public function formatJsonTab($ev)
    {
        $tb = array();
        $keys = array_keys($ev);

        for($i=0; $i < count($ev); $i++) {
            foreach($ev[$keys[$i]] as $key => $value) {
                if($key == 'title') { $tb[$i][$key] = $value; }
                if($key == 'start') {
                    $tb[$i][$key] = $value->format("Y-m-d").$value->format("H:i:s");
                }
                if($key == 'end') {
                    $tb[$i][$key] = $value->format("Y-m-d").$value->format("H:i:s");
                }
                if($key == 'allDay') {
                    $tb[$i][$key] = $value;
                }
                if($key == 'url') {
                    $tb[$i][$key] = $value;
                }
                if($key == 'color') {
                    $tb[$i]['backgroundColor'] = "#".$value;
                    $tb[$i]['borderColor'] = "#ffffff";
                    if($value == "ffff00") { // yellow
                        $tb[$i]['textColor'] = "brown";
                    }
                }
                if($key == 'startHour') {
                    $tb[$i]['start'] = substr($tb[$i]['start'], 0, 10).'T'.$value.':00';
                }
                if($key == 'endHour') {
                    $tb[$i]['end'] = substr($tb[$i]['end'], 0, 10).'T'.$value.':00';
                }
            }
        }
        return $tb;
    }
}