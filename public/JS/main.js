var movInput="O";
function played(selection=0){
    var url = new URL('http://127.0.0.1/selection')
    var params = {selection:selection} 
    var gameId=document.getElementById("gameId").textContent?document.getElementById("gameId").textContent:0;
    var playerId=document.getElementById("playerId").textContent?document.getElementById("playerId").textContent:0;
    var twoPl=(document.getElementById("twoPlayer").checked)?true:false;
    
    //url.search = new URLSearchParams(params).toString();
    fetch(url  ,{
        method: "POST",
        body: JSON.stringify({
        input: selection,
        gameId: gameId,
        playerId: playerId,
        gameStat: getgameStatus(),
        twoPl: twoPl,
        movInput:movInput,

    }),
        headers: {
        'Content-type': 'application/json; charset=UTF-8'
    },})
    .then(function (response) {
        return response.text(); })
    .then(data=>{ 
        applyNewStatus(data);
        
        lbl=document.getElementById("lbltwoPlayer");
        if(movInput=="O" && twoPl){
            document.getElementById("twoPlayer").style.display = "none"; 
            lbl.textContent="Spieler 2 Bewegung (X)";
            movInput="X"
        }else if(movInput=="X" && twoPl){
            lbl.textContent="Spieler 1 Bewegung (O)";
            movInput="O";
        }
        } );
}

function getgameStatus(){
    getAllbtn=document.getElementsByTagName("a"); // all btn
    gameStat=[];
    for(i=0;i<getAllbtn.length;i++){
        btnID=i+1;
        let obj={
        btnID:`${getAllbtn[i].textContent}`, // exp 1:0, 2=X, 3=NULL;
        };
        gameStat.push(obj);
    }
    return gameStat;
    
}

function applyNewStatus(newStatus){
    //console.log(newStatus);
    twoPlayer=document.getElementById("twoPlayer");
    (twoPlayer!=undefined)?twoPlayer.disabled=true:"";
    
    newStatus= JSON.parse(newStatus)
    if(newStatus.length>0){
        for(i=0; i<newStatus.length;i++) {
            if(newStatus[i]["gameID"]){
                document.getElementById("gameId").textContent=newStatus[i]["gameID"];
                continue;
            }
            atag=document.getElementById(`${i+1}`);
            (atag!=undefined)?atag.textContent=newStatus[i]["btnID"]:"do nothing";
            if(newStatus[i]["btnID"]=="O" || newStatus[i]["btnID"]=="X"){
                (atag!=undefined)?atag.className = "nonhidden":"";
            }else{
                (atag!=undefined)?atag.className = "hiddentext":"";
            }
            if(newStatus[i]["result"]){
                checkWinner(newStatus[i]["result"]);
            }
        }
    }else{
        alert("Fehlgeschlagen! aktualisieren Sie Ihre Seite und versuchen Sie es erneut!");
    }

}
function checkWinner(won){
    console.log(won);
    Object.keys(won).forEach(function(key) {
        atag=document.getElementById(`${won[key]}`);
        atag.className = "won";
        checkedBox=document.getElementById("twoPlayer");
        winner=(atag.textContent=="X")?  ((checkedBox.checked==false)? "PC hat das Spiel gewonnen!": " Spieler 2 hat das Spiel gewonnen!"): ((checkedBox.checked==false)? "WOW!, du hast das Spiel gewonnen!": "Spieler 1 hat das Spiel gewonnen!");
    });
    disableAtags();
 
    setTimeout(function () {alert(winner)}, 400); // announce the winner
}
function disableAtags(){
        getAllbtn=document.getElementsByTagName("a"); // all btn
        for(i=1;i<=getAllbtn.length;i++){
            atag=document.getElementById(`${i}`);
            if(atag.textContent=="X" || atag.textContent=="O"){
    
            }else{
                atag.className="hidden_lock";
            }      
        }
}