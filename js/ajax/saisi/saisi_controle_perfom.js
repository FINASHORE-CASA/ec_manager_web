$(document).ready(function() {

    // Selection des indicateurs 
    var btnControle = $("#btn-controle")
        ,formLoader = $("#form-lot-loader");
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

    // Traitement Image saisit en double
    var traitementImagepathDouble =  function(data1) {
            $.post('../../proccess/ajax/saisi/controle_image_double.php',   // url
            { myData: JSON.stringify(data1) }, // data to be submit
            function(data, status, jqXHR) 
            {
                var result = JSON.parse(data);                               
                
                if(result[0] == "success")
                {
                    // success callback
                    // display result    
                    $("#liste-indic li:eq(4)").html("Traitement Image saisit en double : (" + result[1] + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                    $("#liste-indic li:eq(4)").fadeIn(1000);
                    console.log('success : ' + result[1] );

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

    // Traitement Numero Acte saisit en double
    var traitementNumACteDouble = function(data1) {
            $.post('../../proccess/ajax/saisi/controle_num_acte_double.php',   // url
            { myData: JSON.stringify(data1) }, // data to be submit
            function(data, status, jqXHR) 
            {
                var result = JSON.parse(data);                               
                
                if(result[0] == "success")
                {
                    // success callback
                    // display result    
                    $("#liste-indic li:eq(3)").html("Traitement Num_Acte en double : (" + result[1] + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                    $("#liste-indic li:eq(3)").fadeIn(1000);
                    console.log('success : ' + result[1]);                                
                }
                else
                {
                    console.log('message error : ' + result);
                    console.log(result);
                }  

                //Lancement Traitement Numero Acte saisit en double
                traitementImagepathDouble(data1);  
            }
        ); 
    } 

    // Traitement Num Acte ne correspond pas à imagepath
    var traitementNumActeDiffImagepath = function(data1) {
        $.post('../../proccess/ajax/saisi/controle_num_acte_diff_imagepath.php',   // url
            { myData: JSON.stringify(data1) }, // data to be submit
            function(data, status, jqXHR) 
            {
                var result = JSON.parse(data);                               
                
                if(result[0] == "success")
                {
                    // success callback
                    // display result    
                    $("#liste-indic li:eq(2)").html("Traitement Num Acte # imagepath : (" + result[1] + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                    $("#liste-indic li:eq(2)").fadeIn(1000);
                    console.log('success : ' + result[1]);
                }
                else
                {
                    console.log('message error : ' + result);
                    console.log(result);
                }

                // Lancement Traitement Image saisit en double
                traitementNumACteDouble(data1);
            }
        ); 
    }

    // Traitement Numéro Acte vide
    var traitementNumActeVide = function(data1) { 
        $.post('../../proccess/ajax/saisi/controle_num_acte_vide.php',   // url
            { myData: JSON.stringify(data1) }, // data to be submit
            function(data, status, jqXHR) 
            {
                var result = JSON.parse(data);                               
                
                if(result[0] == "success")
                {
                    // success callback
                    // display result    
                    $("#liste-indic li:eq(1)").html("Traitement Numéro Acte Vide : (" + result[1] + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                    $("#liste-indic li:eq(1)").fadeIn(1000);
                    console.log('success : ' + result[1]);
                }
                else
                {
                    console.log('message error : ' + result);
                    console.log(result);
                }

                // Lancement Num Acte ne correspond pas à imagepath
                traitementNumActeDiffImagepath(data1); 
            }
        );   
    }

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
            $.post('../../proccess/ajax/saisi/controle_image_vide.php',   // url
                { myData: JSON.stringify(data1) }, // data to be submit
                function(data, status, jqXHR) 
                {
                    console.log(data);
                    var result = JSON.parse(data);                               
                    
                    if(result[0] == "success")
                    {
                        // success callback
                        // display result    
                        $("#liste-indic li:eq(0)").html("Traitement Image Vide : (" + result[1] + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                        $("#liste-indic li:eq(0)").fadeIn(1000);
                        console.log('success : '  + result[1]);
                        
                        // Lancement du Traitement Numéro Acte vide 
                        traitementNumActeVide(data1);
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