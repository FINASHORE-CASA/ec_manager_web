$(document).ready(function() {

    // Selection des indicateurs 
    var btnControle = $("#btn-controle")
        ,formLoader = $("#form-lot-loader");
    var textListLot = $("#text-list-lot");
    var txtNbLot = $("#txt-nb-lot");
    var txtControleNotif = $("#txt-controle-notif"),
    indicTermine = $("#indic-termine"),notifResultat = $("#notif-Resultat-bell")
    ,ResultatData = $("#resultat_data");

    var startEditActe = function(e) {
        var btnEdit = $(".btn-edit");

        // Remplissage des informations de l'acte
        btnEdit.each(function(i,element){

            element.on("click",function(e){
                alert(i + ' : ' + e.target);        
                // Récupération de l'Id du click
                // var data1 = {
                //     id_lot: textListLot.val().trim().replace(/[\n\r]/g,', '),
                // }     
            });
        });
    }

});