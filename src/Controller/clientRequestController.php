<?php

namespace App\Controller;

use App\Repository\MovementRepository;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use App\Entity\Movement;
use App\gameLogic\ApplyStat;
use App\Controller\MovementRepositor;
use Doctrine\Common\Collections\Expr\Value;
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
        
        $applystatus=new ApplyStat($parameters['input'], $parameters['gameStat'], $parameters['gameId'], $parameters['playerId'], $parameters['twoPl'], $parameters['movInput']);
        $movRepo= $MovementRepository->getAllMovements($applystatus->gameID);// to get history from DB

            $mov= new Movement();
            $mov->setMovement($parameters['input']);
            $mov->setGameId($applystatus->gameID);
            $mov->setPlayerId($applystatus->playerID);
            $mov->setOldmovments((array)$applystatus->applyStatus);

            if($parameters['gameId']){ // here we check the client gameStat against the DB if match preced, if not, avoid sedning data to client.
                $dbmovments="";
                $dbmovments=json_decode($movRepo[0]["oldmovments"]) ;
                unset($dbmovments[9]);
                foreach($dbmovments as $key=>$value){
                    if($parameters['gameStat'][$key]["btnID"]!=$value->btnID){
                       // $applystatus=["difference"=>$value->btnID];
                       return new Response((json_encode(false)) ,Response::HTTP_OK);
                        break;
                    }
                }
            }
            $em = $doctrine->getManager();
            $em->persist($mov);
            $em->flush();
            return new Response(json_encode($applystatus->applyStatus) ,Response::HTTP_OK);
    }
}