$(document).ready(function() 
{
    
    var HostLink = window.location.href.split("/")[0] +"//"+ window.location.href.split("/")[2]+ "/" +window.location.href.split("/")[3];
    HostLink = HostLink.includes(".php") ? "." : HostLink;

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

    var show_alert = function(theme_color,title,text="",time=5)
    {
        $("#alert-container").html('<div id="alert_box" class="alert alert-'+theme_color+' alert-dismissible fade show mt-2" role="alert">'
                                    +'<strong> '+title+' </strong>' + text
                                    +'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                    +'<span aria-hidden="true">&times;</span>'
                                   +'</button>'
                                +'</div>');

        setTimeout(() =>{ $("#alert-container #alert_box").fadeOut("slow");},(time*1000));                                
    }  

    $("#text-list-lot-livre").on("keyup",function(e) 
    {
        var nbLot = countNbLot($(this).val());
        $("#list-notif-idlot-livre").text(nbLot);
    });   

    // Chargement de la liste final
    $("#btn-modif-txt-livre").on("click", function(e)
    {
        $.get(HostLink+'/proccess/ajax/livraison/get_lot_livre.php',
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

        $.post(HostLink+'/proccess/ajax/livraison/save_lot_livre.php',   // url
            { myData: JSON.stringify(data1) }, // data to be submit
            function(data, status, jqXHR) 
            {
                try {
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
                catch (e) {
                    
                    // mis en place de l'alert 
                    show_alert("danger","Erreur lors de la Modification",data,10);
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
            $("#purge-acte-non-finalise")[0].checked = false;                             
        }
        else
        {
            $("#select-choix-purge").removeAttr("disabled");
        }
    });

    // Checks purge acte non conforme
    $("#purge-acte-non-finalise").on("click",function(e)
    {        
        if($(this)[0].checked == true)
        { 
            $("#purge-livre")[0].checked = false;
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

        if(nbLot == 0 && $("#purge-livre")[0].checked == false && $("#purge-acte-non-finalise")[0].checked == false)
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
                $.post(HostLink+'/proccess/ajax/livraison/purge_lot_save.php',   // url
                    { myData: JSON.stringify(data1) }, // data to be submit
                    function(data, status, jqXHR) 
                    {
                        try {                        
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
                                // Terminé le Lancement
                                formLoader.fadeOut("slow");     
                                show_alert("warning","Erreur lors de la Modification : ",result[1],10);
                                console.log('message error : ' + result);
                                console.log(result);
                            }
                        }
                        catch(err)
                        {
                            // mis en place de l'alert 
                            show_alert("danger","Erreur lors de la Modification : ",data,10);
                            formLoader.fadeOut("slow");
                        }
                    }
                ).fail(function(res){                    
                    
                    show_alert("warning","Erreur lors de la Modification : ",res,10);
                    formLoader.fadeOut("slow"); 
                    console.log("fail");
                    console.log(res);
                });
            }  
            else if($("#select-choix-purge").val() == 1)
            {
                if($("#purge-livre")[0].checked == true)
                {                    
                    $.get(HostLink+'/proccess/ajax/livraison/get_lot_livre.php',
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
                                $.post(HostLink+'/proccess/ajax/livraison/purge_lot_delete.php',   // url
                                    { myData: JSON.stringify(data1) }, // data to be submit
                                    function(data, status, jqXHR) 
                                    {                                        
                                        try {
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
                                                formLoader.fadeOut("slow");
                                                console.log('message error : ' + result);
                                                console.log(result);
                                            }
                                        }
                                        catch (err)
                                        {
                                            // mis en place de l'alert 
                                            show_alert("danger","Erreur lors de la Modification",data,10);
                                            formLoader.fadeOut("slow");                                            
                                        }
                                    }
                                ).fail(function(res){
                                    
                                    formLoader.fadeOut("slow");
                                    console.log("fail");
                                    console.log(res);
                                });
                            }
                            else
                            {
                                formLoader.fadeOut("slow");
                                console.log('message error : ' + result);
                                console.log(result);
                            }
                        }
                    ).fail(function(res){
                        
                        formLoader.fadeOut("slow");                        
                        console.log(res);
                    }); 
                }
                else if($("#purge-acte-non-finalise")[0].checked == true)
                {
                    $.get(HostLink+'/proccess/ajax/livraison/delete_acte_non_conform.php',
                    function(data, status, jqXHR)
                    {    
                        try{

                            let result = JSON.parse(data);

                            if(result[0] == 'success')
                            {
                                // display result    
                                $("#liste-indic li:eq(0)").html("Elimination des actes en plus <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                                $("#liste-indic li:eq(0)").fadeIn(1000);

                                // Terminé le Lancement
                                formLoader.fadeOut("slow");
                                indicTermine.fadeIn(3000);  
                            }
                        }    
                        catch(err) {         
                            // mis en place de l'alert 
                            show_alert("danger","Erreur lors de la Modification : ",data,10);               
                            formLoader.fadeOut("slow");   
                            console.log("fail");                     
                            console.log(data);
                        }            
                            
                    }).fail(
                    function(err)
                    {
                        show_alert("danger","Erreur lors de la Modification : ",err,10);               
                        formLoader.fadeOut("slow");
                        console.log("fail");
                        console.log(err);
                    });
                }
                else
                {
                    // cas de purge delete
                    $.post(HostLink+'/proccess/ajax/livraison/purge_lot_delete.php',   // url
                        { myData: JSON.stringify(data1) }, // data to be submit
                        function(data, status, jqXHR) 
                        {
                            try
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
                                    formLoader.fadeOut("slow");
                                    // mis en place de l'alert 
                                    show_alert("danger","Erreur lors de la Modification : ",data,10);
                                    console.log('message error : ' + result);
                                    console.log(result);
                                }
                            }
                            catch(err)
                            {       
                                show_alert("danger","Erreur lors de la Modification : ",data,10);                                                         
                                formLoader.fadeOut("slow");
                                console.log("fail");
                                console.log(err);
                            }
                        }
                    ).fail(function(res){
                        
                        show_alert("danger","Erreur lors de la Modification : ",res,10);                                                
                        formLoader.fadeOut("slow");
                        console.log("fail");
                        console.log(res);
                    });
                }
            }             
        }
        e.preventDefault();        

    });
});