$(document).ready(function() 
{    
    var HostLink = window.location.href.split("/")[0] +"//"+ window.location.href.split("/")[2]+ "/" +window.location.href.split("/")[3];
    HostLink = HostLink.includes(".php") ? "." : HostLink;

    // Selection des indicateurs 
    var btnControle = $("#btn-controle")
        ,formLoader = $("#form-lot-loader");
    var textListLot = $("#text-list-lot");
    var textListLotDispo = $("#text-list-lot-dispo");
    var txtNbLot = $("#txt-nb-lot");
    var txtControleNotif = $("#txt-nb-lot-notif")
    ,indicTermine = $("#indic-termine")
    ,notifResultat = $("#notif-Resultat-bell")
    ,ResultatData = $("#resultat_data");
    textListLot.val("");
    
    var listLotError = "";

    // Préparation des données à envoyer
    var countNbLot = function(txt) 
    {
        var txtArray =  txt.split("\n").filter(function(el) {return el.trim().length != 0});        
        return txtArray.length;
    };     

    var show_alert = function(theme_color,title,text="",time=5)
    {
        $("#alert-container").html('<div id="alert_box" class="alert alert-'+theme_color+' alert-dismissible fade show mt-2" role="alert">'
                                    +'<strong> '+title+' </strong>' + text
                                    +'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                    +'<span aria-hidden="true">&times;</span>'
                                   +'</button>'
                                +'</div>');

        setTimeout(() => { $("#alert-container #alert_box").fadeOut("slow");} , (time*1000));                                
    }             

    btnControle.on('click',function(e)
    {
        var nbLot = countNbLot(textListLot.val());
        listLotError = "";
        $("#text-list-lot-errone").val("");
        $("#indic-lot-dispo").html("0");
        e.preventDefault();        
        console.log(nbLot);
        if(nbLot == 0)
        {
            txtControleNotif.css("color","red");
            txtControleNotif.text("aucun lot renseigné.");
        }
        else
        {
            // hide previous result
            textListLotDispo.fadeOut("slow");
            indicTermine.fadeOut("slow");
            formLoader.css("display","inherit");

            // traitement des lots             
            var data1 = {
                id_lot: textListLot.val().trim().replace(/[\n\r]/g,','),
            }              

            // Traitement Image Vide 
            $.post(HostLink+'/proccess/ajax/SheetApi/getLotDispo.php',   // url
                { myData: JSON.stringify(data1) }, // data to be submit
                    function(data, status, jqXHR) 
                    {
                        console.log(data);
                        var result = JSON.parse(data);                               
                        
                        if(result[0] == "success")
                        {
                            // display result                                                                                                                                                                                      
                            // Ajout de l'IdLot dans les erronés
                            textListLotDispo.val("")
                            $("#indic-lot-dispo").html(result[1].length);
                            result[1].forEach(e=> 
                            {
                                textListLotDispo.val(textListLotDispo.val() + e + "\n")
                            });
                                                        
                            // Terminé le Lancement    
                            textListLotDispo.fadeIn("slow");                                                    
                            formLoader.fadeOut("slow");                        
                            indicTermine.fadeIn(3000);  
                        }
                        else
                        {
                            console.log('message error : ' + result);
                            console.log(result);
                        }
                    }
                );             
        }
    });
});