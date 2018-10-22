const APIURL = "http://51.38.45.203:8080"

var api = {
    getCollection : function(endpoint,callback) {
        $.ajax({
            url: APIURL+"/"+endpoint,
            type: 'GET',
        }).done(function( data ) {
            var liste=data["hydra:member"];
            callback(liste);
        });
    }
}

$(document).ready(function() {

    function functCallback(data) {
        console.log(data)
    }

    api.getCollection("years", functCallback);


});