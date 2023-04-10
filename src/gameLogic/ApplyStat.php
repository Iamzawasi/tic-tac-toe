<?php

namespace App\gameLogic;


class ApplyStat{
    public $applyStatus=[];
    public $currentMove=[];
    public $gameStat;
    public $gameID;
    public $playerID;
    public $playingAgainst;

    function __construct($currentMove, $gameStat, $gameID, $playerID)
    {
        $this->currentMove=$currentMove;
        $this->gameStat=$gameStat;
        $this->gameID=$gameID;
        $this->playerID=$playerID;
        $this->checkgameID();   /// check and validate game ID
        $this->CheckPlayerID(); 
        $this->checkStatus();
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
    public function checkStatus(){
        if(count($this->gameStat)){
            $this->gameStat[$this->currentMove-1]["btnID"]="O";
            $this->applyStatus=$this->gameStat;
            $this->checkWinner();
        }
    }
    public function checkWinner(){
        $playerMovement=array();
        foreach ($this->applyStatus as $key=>$value){
            if($value["btnID"]=="O"){
                array_push($playerMovement, $key+1); 
            }
        }
        //$this->applyStatus=$playerMovement;
        
    }
    public function PCmovement(){

    }
    public function DBstatusCheck(){

    }
    public function ValidateOldMovments($dbHistory, $clientHistory){


    }



}