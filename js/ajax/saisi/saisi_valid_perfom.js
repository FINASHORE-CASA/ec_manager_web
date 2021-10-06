$(document).ready(function() {

    // Selection des indicateurs 
    var btnControle = $("#btn-controle"),formLoader = $("#form-lot-loader");
    var textListLot = $("#text-list-lot");
    var txtNbLot = $("#txt-nb-lot");
    var txtControleNotif = $("#txt-controle-notif"),
    indicTermine = $("#indic-termine");

    // Préparation des données à envoyer
    var countNbLot = function() 
    {
        var txt = textListLot.val();
        var txtArray =  txt.split("\n").filter(function(el) {return el.trim().length != 0});        
        return txtArray.length;
    };   

    btnControle.on('click',function(e)
    {
        var nbLot = countNbLot();
        console.log(nbLot);
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

            // Traitement Image Vide 
            $.post('../../proccess/ajax/saisi/valid_lot.php',   // url
                { myData: JSON.stringify(data1) }, // data to be submit
                function(data, status, jqXHR) 
                {
                    var result = JSON.parse(data);                               
                    
                    if(result[0] == "success")
                    {
                        // success callback
                        // display result    
                        $("#liste-indic li:eq(0)").html(" Validation Lot : (" + result[1].length + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                        $("#liste-indic li:eq(0)").fadeIn(1000);
                        console.log('success : ' + result[1]);
                        console.log(result[1]);

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
            );

        }
        e.preventDefault();        

    });
});