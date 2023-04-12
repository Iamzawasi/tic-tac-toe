<?php

namespace App\Controller;

use App\Repository\MovementRepository;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use App\Entity\Movement;
use App\gameLogic\ApplyStat;
use App\Controller\MovementRepositor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;



class clientRequestController  extends AbstractController{

     /**
     * @Route("/selection", name="input", methods="POST")
     */
    public function request(Request $request, MovementRepository $MovementRepository, PersistenceManagerRegistry $doctrine){
 
        $parameters = json_decode($request->getContent(), true);
        //toDo test if any parameter is not available?-->>>>>>>>>>
        $applystatus=new ApplyStat($parameters['input'], $parameters['gameStat'], $parameters['gameId'], $parameters['playerId'], $parameters['twoPl'], $parameters['movInput']);
        $movRepo= $MovementRepository->getAllMovements($applystatus->checkgameID());// to get history from DB

            $mov= new Movement();
            $mov->setMovement($parameters['input']);
            $mov->setGameId($applystatus->gameID);
            $mov->setPlayerId($applystatus->playerID);
            $mov->setOldmovments((array)$applystatus->applyStatus);

            $em = $doctrine->getManager();
            $em->persist($mov);
            $em->flush();
            return new Response(json_encode($applystatus->applyStatus) ,Response::HTTP_OK);

            // if(rand(1,2)==1){
            //     return new Response(json_encode($applystatus->applyStatus) ,Response::HTTP_OK);
            // }else{
            //     return new Response(json_encode($movRepo) ,Response::HTTP_OK);
            // }        
       //return $movRepo;
       //$em = $this->getDoctrine()->getManager();
      
     
        
       
    }
}