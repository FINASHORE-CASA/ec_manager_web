$(document).ready(function() 
{    
    var HostLink = window.location.href.split("/")[0] +"//"+ window.location.href.split("/")[2]+ "/" +window.location.href.split("/")[3];
    HostLink = HostLink.includes(".php") ? "." : HostLink;

    // Selection des indicateurs 
    var btnSplitBD = $("#btn-repartition"),
    formLoader = $("#form-lot-loader");
    var txtNbLot = $("#txt-nb-lot");
    var textListLot = $("#text-list-lot");
    var txtControleNotif = $("#txt-nb-lot-notif"),    
    indicTermine = $("#indic-termine"),
    fieldNomBd = $("#field-DebNom"),
    fieldRepartission = $("#field-nbLotRepartir");
    textListLot.val("");

    (() => {        
        // Récupération de la source 
        $.get(HostLink+'/proccess/ajax/livraison/get_id_lot.php'
        ,function(data,status,jqXHR)
        {
            var result = JSON.parse(data);                               
            
            if(result[0] == "success")
            {
                // success callback
                result[1].forEach(function(e)
                {
                    $("#text-list-lot").val($("#text-list-lot").val() + e.id_lot + '\n');                    
                    txtControleNotif.css("color","#20c9a6");
                    txtNbLot.css("borderColor","#20c9a6");
                    txtNbLot.css("color","#20c9a6");
                    txtNbLot.text(countNbLot($("#text-list-lot").val()));
                });                
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
    })();

    (() => {
        // Récupération du numero de debut du split 
         $.get(HostLink+'/proccess/ajax/livraison/get_split_numero.php'
        ,function(data,status,jqXHR)
        {
            let res = JSON.parse(data)
            if(res[0] == "success")
            {
                fieldNomBd.val(res[1])
            }
        }).fail(function(res) {
            console.log("fail")
            console.log(res)
        });    
    })();

    // Préparation des données à envoyer
    var countNbLot = function(txt) 
    {
        var txtArray =  txt.split("\n").filter(function(el) {return el.trim().length != 0});        
        return txtArray.length;
    }; 

    function split_db(groupLot,index)
    {
        // Lancement des split en group                
        var dataToSend = 
        {
            id_lot: groupLot[index].list_lots.join(","),
            name_bd:"LIV"+(index + parseInt(fieldNomBd.val())),
            id_group:groupLot[index].idGroup,
            last_group:groupLot.length                  
        }; 

        $.post(HostLink+'/proccess/ajax/livraison/split_bd.php',   // url
            { myData: JSON.stringify(dataToSend) }, // data to be submit
            function(data, status, jqXHR) 
            {                                               
                var result = JSON.parse(data);   
                
                if(result[0] == "success")
                {
                    // Mis à jour de l'indicateur de progression
                    percent_transfert =  Math.ceil(((index+1)/groupLot.length)*100);         
                    $("#indic_text").html("("+(index+1)+"/" + groupLot.length + ")");
                    $("#indic_progress .progress-bar").css("width",percent_transfert + "%");
                    $("#indic_progress .progress-bar").attr("aria-valuenow",percent_transfert);                   
                }
                else
                {                    
                    console.log('message error : ' + result[1]);
                    console.log(result);
                }

                if((index+1) == groupLot.length)
                {                            
                    // Terminé le Lancement
                    $("#indic_progress .progress-bar").removeClass("progress-bar-animated");     
                    formLoader.fadeOut("slow");
                    indicTermine.fadeIn(3000); 

                    // Suppression de la Base de Données de sauvegarde
                    $.get(HostLink+'/proccess/ajax/livraison/split_bd_delete_bd.php'
                    ,function(data,status,jqXHR)
                    {
                        var result = JSON.parse(data);                               
                        
                        if(result[0] == "success")
                        {
                            // success callback
                            console.log("sauvegarde DB Supprimé");
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
                }
                else
                {
                    split_db(groupLot,(index+1)) 
                }
            }
        ).fail(function(res){
            console.log("fail");
            console.log(res);
        });  
    }

    btnSplitBD.on('click',function(e)
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
            if(fieldRepartission.val() != "" && fieldNomBd.val() != "")
            {
                // hide previous result
                indicTermine.fadeOut("slow");
                formLoader.css("display","inherit");    

                // traitement des lots             
                var data1 = {
                    id_lot: $("#text-list-lot").val().trim().replace(/[\n\r]/g,',')
                };
                var nb_spliter = 0,nb_lot_traite=0,percent_transfert = 0,nb_total_lot = 0;
                let listLotsNonSpliter = [];

                // Création de group de lot
                let groupLevel = 1;
                let groupLot = [{
                                idGroup:1,
                                list_lots:[]
                                }];
                let groupReaching = fieldRepartission.val();

                data1.id_lot.split(",").forEach((e,i) => 
                {
                    if((i+1) > (groupReaching * groupLevel))
                    {
                        groupLevel = groupLevel + 1;
                        groupLot.push({idGroup:groupLevel,list_lots:[]});
                        let group = groupLot.find(g => { return g.idGroup == (groupLevel)});                    
                        group.list_lots.push(e);
                    }
                    else
                    {
                        let group = groupLot.find(g => { return g.idGroup == (groupLevel)});                
                        group.list_lots.push(e);
                    }
                });            

                // init indic text
                $("#indic_text").html("(0/" + groupLot.length + ")");
                $("#indic_progress .progress-bar").css("width","0%");
                $("#indic_progress .progress-bar").attr("aria-valuenow","0");     
                $("#indic_progress .progress-bar").addClass("progress-bar-animated");     
                $("#liste-indic").fadeIn();  

                split_db(groupLot,0);
            }
        }
        e.preventDefault();        
    });
});