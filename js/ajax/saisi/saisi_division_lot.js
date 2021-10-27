$(document).ready(function() {

    // Selection des indicateurs 
    var btnDiviser = $("#btn-diviser");
    var textListLotDiviser = $("#text-list-lot-diviser");
    var txtNbLot = $("#txt-nb-lot");
    var txtControleNotifDiviser = $("#txt-nb-lot-notif-diviser"),
    indicTermine = $("#indic-termine-diviser");

    // Préparation des données à envoyer
    var countNbLot = function(txt) 
    {
        var txtArray =  txt.split("\n").filter(function(el) {return el.trim().length != 0});        
        return txtArray.length;
    };  

    $("#btn-diviser-confirm").on("click",function(e)
    {        
        $("#ConfirmDiviserModal").modal("hide");
        var nbLot = countNbLot(textListLotDiviser.val());
        console.log(nbLot);
        if(nbLot == 0)
        {
            txtControleNotifDiviser.css("color","red");
            txtControleNotifDiviser.text("aucun lot renseigné.");
        }
        else
        {
            // hide previous result
            $("#liste-indic-diviser li").fadeOut("slow");
            indicTermine.fadeOut("slow");
            $("#form-lot-loader-diviser").css("display","inherit");

            // traitement des lots             
            var data1 = {
                id_lot: textListLotDiviser.val().trim().replace(/[\n\r]/g,', '),
                nb_division : $("#select-nb-division").val()
            }            

            // Traitement Image Vide 
            $.post('../../proccess/ajax/saisi/diviser_lot.php',   // url
                { myData: JSON.stringify(data1) }, // data to be submit
                function(data, status, jqXHR) 
                {
                    console.log(data);
                    var result = JSON.parse(data);                               
                    
                    if(result[0] == "success")
                    {
                        // success callback
                        // display result    
                        $("#liste-indic-diviser li:eq(0)").html(" <b> Opération terminer : <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'> </b></i>");
                        $("#liste-indic-diviser li:eq(0)").fadeIn(1000);                                                

                        $htmlResultat = " <b> détail : </b>";
                        result.forEach(element => 
                        {
                            if(element != "success")
                            {
                                $htmlResultat += element + " <br/>";   
                            }
                        });

                        $("#liste-indic-diviser li:eq(1)").html($htmlResultat);
                        $("#liste-indic-diviser li:eq(1)").fadeIn(1000);                                                
                        console.log(result);

                        // Terminé le Lancement
                        $("#form-lot-loader-diviser").fadeOut("slow");                        
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
        e.preventDefault(); 
    });

    btnDiviser.on('click',function(e)
    {  
        $("#ConfirmDiviserModal").modal("show");
        e.preventDefault();    
    });    
});