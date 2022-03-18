$(document).ready(function() 
{
    // Selection des indicateurs 
    var btnPurgeConfirm = $("#btn-purge-confirm"),formLoader = $("#form-lot-loader");
    var txtNbLot = $("#txt-nb-lot");
    var txtControleNotif = $("#txt-nb-lot-notif"),
    indicTermine = $("#indic-termine");

    // Préparation des données à envoyer
    var countNbLot = function(txt) 
    {
        var txtArray =  txt.split("\n").filter(function(el) {return el.trim().length != 0});        
        return txtArray.length;
    };   

    $("#text-list-lot-livre").on("keyup",function(e) 
    {
        var nbLot = countNbLot($(this).val());
        $("#list-notif-idlot-livre").text(nbLot);
    });   

    // Chargement de la liste final
    $("#btn-modif-txt-livre").on("click", function(e)
    {
        $.get('../../proccess/ajax/livraison/get_lot_livre.php',
            function(data, status, jqXHR) 
            {
                var result = JSON.parse(data);                               
                
                if(result[0] == "success")
                {
                    // success callback
                    // display result  
                    $("#text-list-lot-livre").val(result[1]);
                    var nbLot = countNbLot(result[1]);
                    $("#list-notif-idlot-livre").text(nbLot);
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
    });

    // Enregistrement de la liste
    $("#btn-purge-livre-confirm").on("click",function()
    {            
        // traitement des lots             
        var data1 = {
            id_lot: $("#text-list-lot-livre").val()
        }         

        $.post('../../proccess/ajax/livraison/save_lot_livre.php',   // url
            { myData: JSON.stringify(data1) }, // data to be submit
            function(data, status, jqXHR) 
            {
                var result = JSON.parse(data);                               
                
                if(result[0] == "success")
                {
                    // success callback
                    // display result    
                    console.log(result[2]);

                    // Terminé le Lancement
                    $("#textLivreModal").modal("hide");      
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
    });

    // Checks purge livré
    $("#purge-livre").on("click",function(e)
    {        
        if($(this)[0].checked == true)
        {
            $("#select-choix-purge").attr("disabled","true");
            $("#select-choix-purge").val("1");                                  
        }
        else
        {
            $("#select-choix-purge").removeAttr("disabled");
        }
    });

    btnPurgeConfirm.on('click',function(e)
    {
        var nbLot = countNbLot($("#text-list-lot").val());    
        $("#ConfirmModal").modal("hide");

        if(nbLot == 0 && $("#purge-livre")[0].checked == false)
        {
            txtControleNotif.css("color","red");
            txtControleNotif.text("aucun lot renseigné.");                    
        }
        else
        {
            // hide previous result
            $("#liste-indic li").fadeOut("slow");
            indicTermine.fadeOut("slow");
            formLoader.css("display","inherit");

            // traitement des lots             
            var data1 = {
                id_lot: $("#text-list-lot").val().trim().replace(/[\n\r]/g,', '),
            }         

            // cas de purge save
            if($("#select-choix-purge").val() == 0)
            {
                $.post('../../proccess/ajax/livraison/purge_lot_save.php',   // url
                    { myData: JSON.stringify(data1) }, // data to be submit
                    function(data, status, jqXHR) 
                    {
                        var result = JSON.parse(data);                               
                        
                        if(result[0] == "success")
                        {
                            // success callback
                            // display result    
                            $("#liste-indic li:eq(0)").html("Lots Purgés (sauvegarde), restants : (" + result[2] + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                            $("#liste-indic li:eq(0)").fadeIn(1000);
                            console.log('success : ' + result[2]);
                            console.log(result[2]);

                            // Terminé le Lancement
                            formLoader.fadeOut("slow");
                            indicTermine.fadeIn(3000);      
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
            }  
            else if($("#select-choix-purge").val() == 1)
            {
                if($("#purge-livre")[0].checked == true)
                {                    
                    $.get('../../proccess/ajax/livraison/get_lot_livre.php',
                        function(data, status, jqXHR) 
                        {
                            var result = JSON.parse(data);                               
                            
                            if(result[0] == "success")
                            {
                                // success callback
                                // traitement des lots             
                                var data1 = {
                                    id_lot: result[1].trim().replace(/[\n\r]/g,', '),
                                }      

                                // cas de purge delete
                                $.post('../../proccess/ajax/livraison/purge_lot_delete.php',   // url
                                    { myData: JSON.stringify(data1) }, // data to be submit
                                    function(data, status, jqXHR) 
                                    {
                                        var result = JSON.parse(data);                               
                                        
                                        if(result[0] == "success")
                                        {
                                            // success callback
                                            // display result    
                                            $("#liste-indic li:eq(0)").html("Lots Purgés (Déja Livrés), restants : (" + result[2] + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                                            $("#liste-indic li:eq(0)").fadeIn(1000);
                                            console.log('success : ' + result[0]);
                                            console.log(result[1]);
                                            console.log(result[2]);
                                            console.log(result[3]);
                                            console.log(result[4]);

                                            // Terminé le Lancement
                                            formLoader.fadeOut("slow");
                                            indicTermine.fadeIn(3000);      
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
                }
                else
                {
                    // cas de purge delete
                    $.post('../../proccess/ajax/livraison/purge_lot_delete.php',   // url
                        { myData: JSON.stringify(data1) }, // data to be submit
                        function(data, status, jqXHR) 
                        {
                            var result = JSON.parse(data);                               
                            
                            if(result[0] == "success")
                            {
                                // success callback
                                // display result    
                                $("#liste-indic li:eq(0)").html("Lots Purgés (suppression), restants : (" + result[2] + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                                $("#liste-indic li:eq(0)").fadeIn(1000);
                                console.log('success : ' + result[0]);
                                console.log(result[1]);
                                console.log(result[2]);
                                console.log(result[3]);
                                console.log(result[4]);

                                // Terminé le Lancement
                                formLoader.fadeOut("slow");
                                indicTermine.fadeIn(3000);      
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
                }
            }             
        }
        e.preventDefault();        

    });
});