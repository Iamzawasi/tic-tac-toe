<?php

namespace App\Controller;
use App\Entity\Movement;


use App\Repository\MovementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

class clientRequestController  extends AbstractController{

     /**
     * @Route("/selection", name="input", methods="POST")
     */
    public function request(Request $request, PersistenceManagerRegistry $doctrine, MovementRepository $MovementRepository){


       $parameters = json_decode($request->getContent(), true);
       $Spiel=new Movement();
       $Spiel->setPlayerId("111");
       $Spiel->setMovement($parameters['input']);

       //$em = $this->getDoctrine()->getManager();
       $em = $doctrine->getManager();
       $em->persist($Spiel);
        $em->flush();

        $movRepo= $MovementRepository->getAllMovements(2);
     
        return new Response(json_encode($movRepo),Response::HTTP_OK);
       
    }
}