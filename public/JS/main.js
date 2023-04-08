
function played(selection=0){
    var url = new URL('http://127.0.0.1/selection')
    var params = {selection:selection} 
    //url.search = new URLSearchParams(params).toString();
    fetch(url  ,{
        method: "POST",
        body: JSON.stringify({
        input: selection,
    }),
        headers: {
        'Content-type': 'application/json; charset=UTF-8'
    },})
    .then(function (response) {
        return response.text(); })
    .then(data=>{ console.log(data);} );
}