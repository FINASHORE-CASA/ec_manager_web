$(document).ready(function() 
{    
    var HostLink = window.location.href.split("/")[0] +"//"+ window.location.href.split("/")[2]+ "/" +window.location.href.split("/")[3];
    HostLink = HostLink.includes(".php") ? "." : HostLink;

    // Selection des indicateurs 
    var btnSplitBD = $("#btn-repartition"),
    formLoader = $("#form-lot-loader");
    var txtNbLot = $("#txt-nb-lot");
    var txtControleNotif = $("#txt-nb-lot-notif"),    
    indicTermine = $("#indic-termine"),
    fieldNomBd = $("#field-DebNom"),
    fieldRepartission = $("#field-nbLotRepartir");

    var get_id_lots = function()
    {
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
                    $("#text-list-lot").val($("#text-list-lot").val() + '\n' + e.id_lot);                    
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
    };
    get_id_lots(); 

    // Préparation des données à envoyer
    var countNbLot = function(txt) 
    {
        var txtArray =  txt.split("\n").filter(function(el) {return el.trim().length != 0});        
        return txtArray.length;
    }; 

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
                nb_total_lot = groupLot.length;

                groupLot.forEach((e,i) =>
                {
                    // Lancement des split en group                
                    var dataToSend = 
                    {
                        id_lot: e.list_lots.join(","),
                        name_bd:"LIV"+(i + parseInt(fieldNomBd.val())),
                        id_group:e.idGroup,
                        last_group:groupLot.length                  
                    }; 
                    
                    $.post(HostLink+'/proccess/ajax/livraison/split_bd.php',   // url
                        { myData: JSON.stringify(dataToSend) }, // data to be submit
                        function(data, status, jqXHR) 
                        {
                            console.log(data);                                                    
                            var result = JSON.parse(data);
                            nb_lot_traite++;   
                            
                            if(result[0] == "success")
                            {
                                // Mis à jour de l'indicateur de progression
                                nb_spliter++;
                                percent_transfert =  Math.ceil((nb_spliter/nb_total_lot)*100);         
                                $("#indic_text").html("("+nb_spliter+"/" + nb_total_lot + ")");
                                $("#indic_progress .progress-bar").css("width",percent_transfert + "%");
                                $("#indic_progress .progress-bar").attr("aria-valuenow",percent_transfert);    
                            }
                            else
                            {
                                listLotsNonSpliter.push(e);
                                $("#indic_progress .progress-bar").removeClass("bg-success");
                                $("#indic_progress .progress-bar").addClass("bg-danger");
                                console.log('message error : ' + result[1]);
                                console.log(result);
                            }

                            if(nb_lot_traite == nb_total_lot)
                            {                            
                                // Terminé le Lancement
                                $("#indic_progress .progress-bar").removeClass("progress-bar-animated");     
                                formLoader.fadeOut("slow");
                                indicTermine.fadeIn(3000); 

                                $("#lot_non_transfere").fadeIn("slow");
                                var htmlNomTrans = "";
                                listLotsNonSpliter.forEach((ele) => {
                                    htmlNomTrans = htmlNomTrans + '<p class="card-text"> ' + ele.idGroup + ' </p >'
                                })
                                $("#lot_non_trans").html(htmlNomTrans);

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
                        }
                    ).fail(function(res){
                        console.log("fail");
                        console.log(res);
                    });  
                });      
            }
        }
        e.preventDefault();        
    });
});