$(document).ready(function() 
{
    
    var HostLink = window.location.href.split("/")[0] +"//"+ window.location.href.split("/")[2]+ "/" +window.location.href.split("/")[3];
    HostLink = HostLink.includes(".php") ? "." : HostLink;

    // Selection des indicateurs 
    var btnTransfert = $("#btn-transfert"),formLoader = $("#form-lot-loader");
    var txtNbLot = $("#txt-nb-lot");
    var txtControleNotif = $("#txt-nb-lot-notif"),
    indicTermine = $("#indic-termine");
    $("#text-list-lot").val("");

    // Préparation des données à envoyer
    var countNbLot = function(txt) 
    {
        var txtArray =  txt.split("\n").filter(function(el) {return el.trim().length != 0});        
        return txtArray.length;
    };   

    // Récupération de la source 
    $.get(HostLink+'/proccess/ajax/livraison/get_liste_source.php'
    ,function(data,status,jqXHR)
    {
        var result = JSON.parse(data);                               
        
        if(result[0] == "success")
        {
            // success callback
            $("#text-list-source").val(result[1]);
        }
        else
        {
            console.log('message error : ' + result);
            console.log(result);
        }
    })
    .fail(function(res){
        console.log("fail");
        console.log(res);
    });  

    // Récupération de la destination 
    $.get(HostLink+'/proccess/ajax/livraison/get_destination.php'
    ,function(data,status,jqXHR)
    {
        var result = JSON.parse(data);                               
        
        if(result[0] == "success")
        {
            // success callback
            $("#text-destination").val(result[1]);
        }
        else
        {
            console.log('message error : ' + result);
            console.log(result);
        }
    })
    .fail(function(res){
        console.log("fail");
        console.log(res);
    });  

    // Enregistrement de la source
    $("#btn-save-source").on("click",function(e) 
    {             
        // récupération de la source
        var data1 = {
            liste_source: $("#text-list-source").val()
        }        

        $.post(HostLink+'/proccess/ajax/livraison/save_liste_source.php',   // url
            { myData: JSON.stringify(data1) }, // data to be submit
            function(data, status, jqXHR) 
            {
                var result = JSON.parse(data);                               
                
                if(result[0] == "success")
                {
                    // success callback
                    console.log('success');
                    console.log(result);
                }
                else
                {
                    console.log('message error : ' + result);
                    console.log(result);
                }
            }
        ).fail(function(res){
            console.log("fail");
            console.log(res);
        });
        e.preventDefault();
    });

    // Enregistrement de la destination     
    $("#btn-save-destination").on("click",function(e) {
             
        // récupération de la source
        var data1 = {
            liste_source: $("#text-destination").val()
        }        

        $.post(HostLink+'/proccess/ajax/livraison/save_destination.php',   // url
            { myData: JSON.stringify(data1) }, // data to be submit
            function(data, status, jqXHR) 
            {
                var result = JSON.parse(data);                               
                
                if(result[0] == "success")
                {
                    // success callback
                    console.log('success');
                    console.log(result);
                }
                else
                {
                    console.log('message error : ' + result);
                    console.log(result);
                }
            }
        ).fail(function(res){
            console.log("fail");
            console.log(res);
        });
        e.preventDefault();
    });

    $("#text-list-lot-livre").on("keyup",function(e) 
    {
        var nbLot = countNbLot($(this).val());
        $("#list-notif-idlot-livre").text(nbLot);
    });   

    function CopyLot(data1,index,source,destination)
    {
        // Récupération de l'élement
        var dataFinal = {
            id_lot: data1[index],
            source: source,
            destination: destination
        }                        

        // transfert lot 
        $.post(HostLink+'/proccess/ajax/livraison/transfert_lot.php',   // url
            { myData: JSON.stringify(dataFinal) }, // data to be submit
            function(data, status, jqXHR) 
            {
                var result = JSON.parse(data);                               
                
                if(result[0] == "success")
                {
                    // Mis à jour de l'indicateur de progression
                    percent_transfert =  Math.ceil(((index+1)/data1.length)*100);         
                    $("#indic_text").html("("+(index+1)+"/" + data1.length + ")");
                    $("#indic_progress .progress-bar").css("width",percent_transfert + "%");
                    $("#indic_progress .progress-bar").attr("aria-valuenow",percent_transfert);    
                }
                else
                {                
                    $("#lot_non_transfere").fadeIn("slow");
                    let htmlNomTrans = '<p class="card-text"> ' + dataFinal.id_lot + ' ('+ result[1] +') </p> \n';
                    $("#lot_non_trans").html($("#lot_non_trans").html() + htmlNomTrans);                                 

                    console.log('message error : ' + result[1]);
                    console.log(result);
                }

                if((index+1) == data1.length)
                {
                    // Terminé le Lancement
                    $("#indic_progress .progress-bar").removeClass("progress-bar-animated");     
                    formLoader.fadeOut("slow");
                    indicTermine.fadeIn(3000); 
                }
                else
                {
                    CopyLot(data1,(index+1),source,destination);
                }
            }
        ).fail(function(res){
            console.log("fail");
            console.log(res);
        });  
    }


    btnTransfert.on('click',function(e)
    {
        var nbLot = countNbLot($("#text-list-lot").val());    
        $("#indic_progress .progress-bar").removeClass("bg-danger");
        $("#indic_progress .progress-bar").addClass("bg-success");
        $("#lot_non_transfere").css("display","none");
        $("#lot_non_trans").html("");

        if(nbLot == 0)
        {
            txtControleNotif.css("color","red");
            txtControleNotif.text("aucun lot renseigné.");                    
        }
        else
        {
            // hide previous result
            indicTermine.fadeOut("slow");
            formLoader.css("display","inherit");    

            // traitement des lots             
            var data1 = {
                id_lot: $("#text-list-lot").val().trim().replace(/[\n\r]/g,','),
                source: $("#text-list-source").val().trim().replace(/[\n\r]/g,','),
                destination : $("#text-destination").val().trim()
            };
            var nb_transfert = 0,nb_lot_traite=0,percent_transfert = 0,nb_total_lot = data1.id_lot.split(",").length;
            let listLotNonTrans = [];

            // init indic text
            $("#indic_text").html("(0/" + data1.id_lot.split(",").length + ")");
            $("#indic_progress .progress-bar").css("width","0%");
            $("#indic_progress .progress-bar").attr("aria-valuenow","0");     
            $("#indic_progress .progress-bar").addClass("progress-bar-animated");     
            $("#liste-indic").fadeIn();      

            // Lancement des copies pas en serie
            console.log(data1.id_lot.split(","));
            CopyLot(data1.id_lot.split(","),0,$("#text-list-source").val().trim().replace(/[\n\r]/g,','),$("#text-destination").val().trim());
        }
        e.preventDefault();       
    });
});