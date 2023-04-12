<?php

namespace App\gameLogic;

use Doctrine\Common\Collections\Expr\Value;

class ApplyStat{
    public $applyStatus=[]; // status to be sent to client PC (new)
    public $gameStat=[];   // it includes the game status (old)
    public $currentMove; // it saves the most current move
    public $gameID;
    public $playerID;
    public $playingAgainst;
    public $gameEnded=false;
    public $twoPlayer=false;
    function __construct($currentMove, $gameStat, $gameID, $playerID, $twoPlayer, $movInput)
    {
        $this->currentMove=$currentMove;
        $this->twoPlayer=$twoPlayer;
        $this->gameStat=$gameStat;
        $this->gameID=$gameID;
        $this->playerID=$playerID;
        $this->checkgameID();   /// check, validate game ID and produce game ID
        $this->CheckPlayerID(); // Check and assign player ID
        $this->playingAgainst=(!$twoPlayer)? "pc": "1";
        if($this->playingAgainst=="pc"){
            $this->checkStatus("O");
            $this->PlayerMovement("O"); //it checks all movements and then call winner function to detect win
            $this->AImovement();
            $this->SetGameID();
        }else{ // two players same PC
            $this->checkStatus($movInput);
            $this->PlayerMovement($movInput); //it checks all movements and then call winner function to detect win
            $this->SetGameID();
        }
    }
    public function PlayerMovement($movChar="O"){
        $playerMovement=array();
        foreach ($this->applyStatus as $key=>$value){   /// step is to create all movement array and then check winner
            if(isset($value["btnID"]) && $value["btnID"]==$movChar){
                array_push($playerMovement, $key+1); 
            }
        }
        $isWinner=array();
        
        $isWinner=$this->checkWinner($playerMovement);
        if(count((array)$isWinner)>2){
            array_push($this->applyStatus, ["result"=>$isWinner]); // when someone wins then Add winner array with result for client
            $this->gameEnded=true;
        }
    }
    public function  checkgameID(){
        if($this->gameID==0 || (!$this->gameID)){   
            $this->gameID=uniqid();
        }
    }
    public function CheckPlayerID(){
        if($this->playerID==0){
            $this->playerID==1;
            $this->playingAgainst=2;
        }
    }
    public function checkStatus($movChar){  // Apply movement
        if(count($this->gameStat)){
            if(count($this->applyStatus)){
                $this->gameStat=$this->applyStatus;
            }
            $this->gameStat[$this->currentMove-1]["btnID"]=$movChar; // find the spot change value
            $this->applyStatus=$this->gameStat; //update new array for status
        }
    }
    public function checkWinner($playerMovement){
        $possWin=$this->WinPossib();
        for($i=0; $i<count($possWin);$i++){
            $result=array_intersect($playerMovement,$possWin[$i]);
            if(count($result)>2){
                return $result;
            }
        }
        return 0;        
    }
    public function WinPossib(){
        $possWin=array(
            array(1,2,3),
            array(4,5,6),
            array(7,8,9),
            array(1,4,7),
            array(2,5,8),
            array(3,6,9),
            array(1,5,9),
            array(3,5,7),
            );
        return $possWin;
    }
    public function AImovement($movChar="X"){
        $possWin=$this->WinPossib(); // possible winining movements
        $altMov=($movChar=="O")?"X":"O"; // find Competitors movements
        $altMoves=array();
        if(!$this->gameEnded){ // if game ended is true do not execute this if
            $hisOwnMov=array();
            foreach ($this->applyStatus as $key=>$value){   // produce array of AI movments and competitors moves 
                if(isset($value["btnID"]) && $value["btnID"]==$movChar){ //check for "X" moves
                    array_push($hisOwnMov, $key+1); 
                }elseif(isset($value["btnID"]) && $value["btnID"]==$altMov){ // check "O" moves
                    array_push($altMoves, $key+1); 
                }
            }
            foreach($possWin as $key=> $innerArray){ // check winning posibilities
                $canWin=array(); // here we check AI wining possibilites
                $canWin=array_intersect($innerArray, $hisOwnMov); //$innerarray are wining posibilites  
                $altCanWin=array_intersect($innerArray, $altMoves); // competitors wining chances
                if(count($canWin)==2){  // here we check if we have already have two movements only third is required to win
                    $success=$this->AImovmentValiditor($innerArray, $hisOwnMov);
                    if($success){
                        return true;
                    }
                }
                elseif(count($altCanWin)==2){  // if the competitor is winning then avoid it
                    $success=$this->AImovmentValiditor($innerArray, $altMoves);
                    if($success){
                        return true;
                    }
                }
            }
            $this->AImovmentValiditor(); // make a random movement
            return true;

        }
        
    }
    public function AImovmentValiditor($innerArray=array(), $setofMovments=array()){ // empty array as default value
        $movChar="X";
        $temp=array_diff($innerArray, $setofMovments); // we find here final moves for wining
        $possiMov=true;
        foreach($temp as $key => $value){
            $toWinSpot=$this->applyStatus[$value-1]["btnID"];
            if($toWinSpot!="X" && $toWinSpot!="O"){ // we check if final movment is empty 
                $this->currentMove=(int)$value;
                $this->checkStatus($movChar);
                $possiMov=false;// to disable double executions
                $this->PlayerMovement($movChar); // update move and announce winner
                return true;
            }
            else{
                return false;
            }
        }
        $emptyCell=$this->getEmptycells();
        if($emptyCell && $possiMov){
            $this->currentMove=array_rand($emptyCell)+1;
            $this->checkStatus($movChar);
            $this->PlayerMovement($movChar);  //update move and announce winner
            return true;
        }
    }
    public function getEmptycells(){ // it produces general empty spots in game
        $emptyCell=$this->applyStatus;
        foreach($this->applyStatus as $key =>$value){
            if(isset($value["result"]) || isset($value["btnID"]) && ($value["btnID"]=="O" || $value["btnID"]=="X")){
                unset($emptyCell[$key]);
            }
        }
        return $emptyCell;
    }
    public function SetGameID(){
        array_push($this->applyStatus, ["gameID"=>$this->gameID]);      
    }
}