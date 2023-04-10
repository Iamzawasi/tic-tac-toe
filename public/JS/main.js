function played(selection=0){
    var url = new URL('http://127.0.0.1/selection')
    var params = {selection:selection} 
    var gameId=document.getElementById("gameId").textContent?document.getElementById("gameId").textContent:0;
    var playerId=document.getElementById("playerId").textContent?document.getElementById("playerId").textContent:0;
    //url.search = new URLSearchParams(params).toString();
    fetch(url  ,{
        method: "POST",
        body: JSON.stringify({
        input: selection,
        gameId: gameId,
        playerId: playerId,
        gameStat: getgameStatus(),

    }),
        headers: {
        'Content-type': 'application/json; charset=UTF-8'
    },})
    .then(function (response) {
        return response.text(); })
    .then(data=>{ 
        applyNewStatus(data);
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
    newStatus= JSON.parse(newStatus)
    if(newStatus.length>0){
        for(i=0; i<newStatus.length;i++) {
            atag=document.getElementById(`${i+1}`);
            atag.textContent=newStatus[i]["btnID"];
            if(newStatus[i]["btnID"]=="O" || newStatus[i]["btnID"]=="X"){
                atag.className = "nonhidden";
            }else{
                atag.className = "hiddentext";
            }
         };
    }

}