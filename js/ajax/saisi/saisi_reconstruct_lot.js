$(document).ready(function() {

    // Selection des indicateurs 
    var btnFusion = $("#btn-fusion");
    var textListLotFusion = $("#text-list-lot-fusion");
    var txtNbLot = $("#txt-nb-lot");
    var txtControleNotifFusion = $("#txt-nb-lot-notif-fusion"),
    indicTermine = $("#indic-termine-fusion");

    // Préparation des données à envoyer
    var countNbLot = function(txt) 
    {
        var txtArray =  txt.split("\n").filter(function(el) {return el.trim().length != 0});        
        return txtArray.length;
    };  

    $("#btn-fusion-confirm").on("click",function(e)
    {
        $("#ConfirmFusionModal").modal("hide");
        var nbLot = countNbLot(textListLotFusion.val());
        console.log(nbLot);
        if(nbLot == 0)
        {
            txtControleNotifFusion.css("color","red");
            txtControleNotifFusion.text("aucun lot renseigné.");
        }
        else
        {
            // hide previous result
            $("#liste-indic-fusion li").fadeOut("slow");
            indicTermine.fadeOut("slow");
            $("#form-lot-loader-fusion").css("display","inherit");

            // traitement des lots             
            var data1 = {
                id_lot: textListLotFusion.val().trim().replace(/[\n\r]/g,', '),
                nb_division : $("#select-nb-fusion").val()
            }            

            // Traitement Image Vide 
            $.post('../../proccess/ajax/saisi/reconstruct_lot.php',   // url
                { myData: JSON.stringify(data1) }, // data to be submit
                function(data, status, jqXHR) 
                {
                    console.log(data);
                    var result = JSON.parse(data);                               
                    
                    if(result[0] == "success")
                    {
                        // success callback
                        // display result    
                        $("#liste-indic-fusion li:eq(0)").html(" <b> Opération terminer : <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'> </b></i>");
                        $("#liste-indic-fusion li:eq(0)").fadeIn(1000);                                                

                        $htmlResultat = " <b> détail : </b>";
                        result.forEach(element => 
                        {
                            if(element != "success")
                            {
                                $htmlResultat += element + " <br/>";   
                            }
                        });

                        $("#liste-indic-fusion li:eq(1)").html($htmlResultat);
                        $("#liste-indic-fusion li:eq(1)").fadeIn(1000);                                                
                        console.log(result);

                        // Terminé le Lancement
                        $("#form-lot-loader-fusion").fadeOut("slow");                        
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

    btnFusion.on('click',function(e)
    {
        $("#ConfirmFusionModal").modal("show");
        e.preventDefault();        
    });
});