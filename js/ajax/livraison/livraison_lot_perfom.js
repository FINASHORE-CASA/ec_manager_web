$(document).ready(function() 
{
    // Selection des indicateurs 
    var btnPurgeConfirm = $("#btn-purge-confirm"),formLoader = $("#form-lot-loader");
    var textListLot = $("#text-list-lot");
    var txtNbLot = $("#txt-nb-lot");
    var txtControleNotif = $("#txt-nb-lot-notif"),
    indicTermine = $("#indic-termine");

    // Préparation des données à envoyer
    var countNbLot = function() 
    {
        var txt = textListLot.val();
        var txtArray =  txt.split("\n").filter(function(el) {return el.trim().length != 0});        
        return txtArray.length;
    };   

    btnPurgeConfirm.on('click',function(e)
    {
        var nbLot = countNbLot();
        console.log(nbLot);
        $("#ConfirmModal").modal("hide");

        console.log($("#select-choix-purge").val());

        if(nbLot == 0)
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
                id_lot: textListLot.val().trim().replace(/[\n\r]/g,', '),
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
                            $("#liste-indic li:eq(0)").html("Lots Purgés (gardé), restants : (" + result[2] + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
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
                            $("#liste-indic li:eq(0)").html("Lots Purgés (suppimé), restants : (" + result[2] + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
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
        e.preventDefault();        

    });
});