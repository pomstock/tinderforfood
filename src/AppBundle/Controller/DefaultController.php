<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Posting;
use AppBundle\Entity\Request as PostingRequest;

/**
 * Collection get action
 * @var Request $request
 * @return array
 *
 * @Annotations\View()
 */
class DefaultController extends FOSRestController
{    
	function filters($request, $init = array()) {
		$filters = $init;
		foreach($request->query->all() as $k=>$v) {
			$filters[$k] = $v;			
		}
		return array_filter($filters);
	}
    public function getPostingsAction(Request $request) {
    	$em = $this->getDoctrine()->getManager();
    	$entities = $em->getRepository('AppBundle:Posting')->findBy($this->filters($request));
    	 
    	return array(
    			'postings' => $entities,
    	);
    }
    
    public function getUserPostingsAction(Request $request, $userID) {
    	$em = $this->getDoctrine()->getManager();
    	$entities = $em->getRepository('AppBundle:Posting')->findBy($this->filters($request, array('sellerId' => $userID)));
    	
    	return array(
    			'postings' => $entities,
    	);
    }
    
    public function getPostingRequestsAction(Request $request, $postingID) {
    	$em = $this->getDoctrine()->getManager();
    	$entities = $em->getRepository('AppBundle:Request')->findBy($this->filters($request, array('postingId' => $postingID)));
    	 
    	return array(
    			'requests' => $entities,
    	);
    }
    
    public function getUserRequestsAction(Request $request, $userID ) {    	
    	$em = $this->getDoctrine()->getManager();
    	$entities = $em->getRepository('AppBundle:Request')->findBy($this->filters($request, array('buyerId' => $userID)));
    	 
    	return array(
    			'requests' => $entities,
    	);
    }
    
    public function postPostingsAction(Request $request) {    
    	$file = $request->files->get('image');
    	if($file) {
    		$fileName = md5(uniqid()).'.'.$file->guessExtension();
    		
    		$file->move(
    				$this->getParameter('images_directory'),
    				$fileName
    				);
    		
    		$image = $fileName;
    	}
    	if(!isset($image)) {
    		$image = $request->request->get('image');
    	}
    	$em = $this->getDoctrine()->getManager();
	    $posting = new Posting();
	    $posting->setDescription($request->request->get('description'));
	    $posting->setImage($image);
	    $posting->setPrice($request->request->get('price'));
	    $posting->setSellerId($request->request->get('sellerId'));
	    $posting->setStatus($request->request->get('status'));
	    $posting->setTitle($request->request->get('title'));
	    
	    $em->persist($posting);
	    $em->flush();
	
    	return array($posting);
    }
    
    public function postRequestsAction(Request $r) {
    	$em = $this->getDoctrine()->getManager();
    	$request = new PostingRequest();
    	$request->setPostingId($r->request->get('postingId'));
    	$request->setText($r->request->get('text'));
    	$request->setBuyerId($r->request->get('buyerId'));
    	 
    	$em->persist($request);
    	$em->flush();
    
    	return array($request);
    }    
    
    
}
