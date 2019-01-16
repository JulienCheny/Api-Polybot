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
        document.getElementById("p1").innerHTML =JSON.stringify(data, null, 2);
    }

    $('#example').DataTable( {
        "autoWidth": false,
        "order": [[ 0, "desc" ]],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url":APIURL+"/requests",
            "type": "GET",
            "dataSrc": "hydra:member",
            /*"data": {
                id: $("#expedientes").val()
            },*/
            "success": function(d){
                console.log(d);
            },
        },
        "columns": [
            { "data": "id"}
        ]
    } );

    api.getCollection("years", functCallback);


});