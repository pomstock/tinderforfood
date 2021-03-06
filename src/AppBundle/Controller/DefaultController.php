<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Posting;
use AppBundle\Entity\Request as PostingRequest;
use AppBundle\Entity\User;

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
    	$postings = $em->getRepository('AppBundle:Posting')->findBy($this->filters($request));
    	foreach($postings as $p) $this->enrichPosting($p);
    	return array(
    			'postings' => $postings,
    	);
    }
    
    function enrichPosting($p) {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$p->setSeller(
    			$em->getRepository('AppBundle:User')->findOneById($p->getSellerId())
    			);
    	$this->enrichUser($p->getSeller());
    	$p->setRequests(
    			$em->getRepository('AppBundle:Request')->findByPostingId($p->getId())
    			);
    	foreach($p->getRequests() as $r){
    		$r->setBuyer(
    			$em->getRepository('AppBundle:User')->findOneById($r->getBuyerId())
    			);
    	}    	
    	 
    }
    
    function enrichRequest($r) {
    	$em = $this->getDoctrine()->getManager();
    
    	$r->setBuyer(
    			$em->getRepository('AppBundle:User')->findOneById($r->getBuyerId())
    			);
    	$this->enrichUser($r->getBuyer());    	
    	$r->setPosting(
    			$em->getRepository('AppBundle:Posting')->findOneById($r->getPostingId())
    			);
    
    }    
    
    public function getUserPostingsAction(Request $request, $userId) {
    	$em = $this->getDoctrine()->getManager();
    	$postings = $em->getRepository('AppBundle:Posting')
    	->findBy($this->filters($request, array('sellerId' => $userId)), array('createdAt' => 'DESC'), 1);
    	foreach($postings as $p) {
    		$this->enrichPosting($p);
    	}
    	return array(
    			'postings' => $postings,
    	);
    }
    
    public function getPostingRequestsAction(Request $request, $postingId) {
    	$em = $this->getDoctrine()->getManager();
    	$requests = $em->getRepository('AppBundle:Request')->findBy($this->filters($request, array('postingId' => $postingId)));
    	foreach($requests as $r) $this->enrichRequest($r);
    	return array(
    			'requests' => $requests,
    	);
    }
    
    public function getUserRequestsAction(Request $request, $userId ) {    	
    	$em = $this->getDoctrine()->getManager();
    	$requests = $em->getRepository('AppBundle:Request')->findBy($this->filters($request, array('buyerId' => $userId)));
    	foreach($requests as $r) $this->enrichRequest($r);
    	 
    	return array(
    			'requests' => $requests,
    	);
    }
    
    public function postPostingsAction(Request $request) {    
    	$file = $request->files->get('image');
    	//return $request->request->all();
    	$logger = $this->get('logger');
    	if($file) {
    		$fileName = md5(uniqid()).'.'.$file->guessExtension();
    		
    		$file->move(
    				$this->getParameter('images_directory'),
    				$fileName
    				);
    		
    		$image = $fileName;
    	}
    	if(!isset($image)) {
    		$fileName = md5(uniqid()).'.jpg';
    		
    		$image = $request->request->get('image');
    		$this->base64_to_jpeg($image,$this->getParameter('images_directory').'/'.$fileName);
    		$image = 'http://foodhack.bonnejournee.ch/web/uploads/images/'.$fileName;
    		$logger->info($image);
    		
    		// $image = $this->base64_to_jpeg($image,'test.jpg');
    	}
    	$em = $this->getDoctrine()->getManager();
	    $posting = new Posting();
	    $this->setFromRequest($request, $posting);
	    $co2 = mt_rand(100,1000);
	    $posting->setCo2Saved($co2);
	    $posting->setPoints($this->getPointsFromCO2($co2));
	    $posting->setImage($image);
	    
	    $em->persist($posting);
	    $em->flush();	     
    	return $posting;
    }
    
    function base64_to_jpeg($base64_string, $output_file) {
    	$ifp = fopen($output_file, "wb");
    
    	$data = explode(',', $base64_string);
    
    	fwrite($ifp, base64_decode($data[1]));
    	fclose($ifp);
    
    	return $output_file;
    }
    
    
    function getPointsFromCO2($co2) {
    	return ceil(-2+15*log($co2,exp(1)));
    }
    
    function setFromRequest(Request $r, $entity) {
    	foreach ($r->request->all() as $k => $v) {
    		$func = 'set'.ucfirst($k);
    		if(method_exists($entity,$func)) $entity->{$func}($v);
    	}
    }
    
    public function postPostingRequestsAction(Request $r, Posting $posting) {
    	$em = $this->getDoctrine()->getManager();
    	$request = new PostingRequest();
    	$this->setFromRequest($r, $request);
    	$request->setPostingId($posting->getId());
    	$request->setCo2Saved($posting->getCo2Saved());
    	$request->setPoints($posting->getPoints());
    	$em->persist($request);
    	$em->flush();
    
    	return $request;
    }    
    
    public function postUsersAction(Request $r) {
    	$em = $this->getDoctrine()->getManager();
    	$user = new User();
    	$this->setFromRequest($r, $user);
    	$em->persist($user);
    	$em->flush();
    
    	return $user;
    }
    
    
    public function patchRequestAction(Request $r, PostingRequest $request) {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$this->setFromRequest($r, $request);
    
    	$em->persist($request);
    	$em->flush();
    
    	return $request;
    }
    
    public function patchUserAction(Request $r, User $user) {
    	$em = $this->getDoctrine()->getManager();
    
    	$this->setFromRequest($r, $user);
    
    	$em->persist($user);
    	$em->flush();
    
    	return $user;
    }
    
    public function getUserIgnoreAction(Request $r, User $user, Posting $p) {
    	$em = $this->getDoctrine()->getManager();
    	$user->setLastPostingSeen($p->getId());    
    	$em->persist($user);
    	$em->flush();
    
    	return $user;
    }    
    
    public function patchPostingAction(Request $r, Posting $posting) {
    	$em = $this->getDoctrine()->getManager();
    	$this->setFromRequest($r, $posting);
    	$em->persist($posting);
    	$em->flush();
    
    	return $posting;
    }
    
    public function nextUserPostingsAction(Request $r, User $user) {
    	$em = $this->getDoctrine()->getManager();
    	$postings = $em->getRepository('AppBundle:Posting')
    	->createQueryBuilder('p')
	    ->where('p.id > :lastId')
	    ->andWhere('p.status = :status')
	    ->andWhere('p.sellerId != :userId')
	    ->setParameter('lastId', $user->getLastPostingSeen())
	    ->setParameter('status', 'open')
	    ->setParameter('userId', $user->getId())
	    ->orderBy('p.id', 'ASC')
	    ->getQuery()
    	->getResult();
	    foreach($postings as $p) $this->enrichPosting($p);
	     
	    return array('postings' => $postings);
    }
    
    function enrichUser($user) {
    	if(!$user) return;
    	$em = $this->getDoctrine()->getManager();
    	$postings = $em->getRepository('AppBundle:Posting')->findBy(array('sellerId' => $user->getId(), 'status' => 'closed'));
    	$requests = $em->getRepository('AppBundle:Request')->findBy(array('buyerId' => $user->getId(), 'status' => 'won'));
    	
    	$sumPoints = function($carry, $p){
    		$carry += $p->getPoints();
    		return $carry;
    	};
    	 
    	$sumCO2 = function($carry, $p){
    		$carry += $p->getCo2Saved();
    		return $carry;
    	};
    	$points = array_reduce($postings, $sumPoints, 0);
    	$points = array_reduce($requests, $sumPoints, $points);
    	$user->setPoints($points);
    	 
    	$co2_saved = array_reduce($postings, $sumCO2, 0);
    	$co2_saved = array_reduce($requests, $sumCO2, $co2_saved);
    	$user->setCo2Saved($co2_saved);
    }
    
    public function getUserAction(User $user) {
		$this->enrichUser($user);
    	return $user;
    }
    
}
