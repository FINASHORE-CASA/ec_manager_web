$(document).ready(function() {

    // Selection des indicateurs 
    var btnControle = $("#btn-controle")
        ,formLoader = $("#form-lot-loader");
    var textListLot = $("#text-list-lot");
    var txtNbLot = $("#txt-nb-lot");
    var txtControleNotif = $("#txt-controle-notif"),
    indicTermine = $("#indic-termine"),notifResultat = $("#notif-Resultat-bell")
    ,ResultatData = $("#resultat_data");

    // Préparation des données à envoyer
    var countNbLot = function() 
    {
        var txt = textListLot.val();
        var txtArray =  txt.split("\n").filter(function(el) {return el.trim().length != 0});        
        return txtArray.length;
    };   

    var initDataTable = function(dataTable) 
    {
        dataTable.DataTable({
            "language": {
                "sProcessing": "Traitement en cours ...",
                "sLengthMenu": "Afficher _MENU_ lignes",
                "sZeroRecords": "Aucun résultat trouvé",
                "sEmptyTable": "Aucune donnée disponible",
                "sInfo": "Lignes _START_ à _END_ sur _TOTAL_",
                "sInfoEmpty": "Aucune ligne affichée",
                "sInfoFiltered": "(Filtrer un maximum de_MAX_)",
                "sInfoPostFix": "",
                "sSearch": "Chercher:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Chargement...",
                "oPaginate": {
                "sFirst": "Premier", "sLast": "Dernier", "sNext": "Suivant", "sPrevious": "Précédent"
                },
                "oAria": {
                "sSortAscending": ": Trier par ordre croissant", "sSortDescending": ": Trier par ordre décroissant"
                }
            }
        });
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

                    $("#notif-Resultat-5").text(result[2].length);

                    // injection des données                             
                    htmlDataTable = "";    
                    result[2].forEach(e => {                            
                        htmlDataTable += "<tr><td>" + e.id_lot + "</td><td>" + e.id_acte +  "</td><td>" + e.num_acte + "</td><td>" + e.imagepath 
                                                + "</td><td>" + e.nom_fr + "</td><td>" + e.prenom_fr + "</td><td>" + e.nom_ar + "</td><td>" + e.prenom_ar + "</td></tr>";
                    });

                    $("#TableImageSaisitDouble").html(htmlDataTable);
                    initDataTable($('#dataTableImageSaisitDouble'));   

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

                    $("#notif-Resultat-4").text(result[2].length);

                    // injection des données                             
                    htmlDataTable = "";    
                    result[2].forEach(e => {                            
                        htmlDataTable += "<tr><td>" + e.id_lot + "</td><td>" + e.id_acte +  "</td><td>" + e.num_acte + "</td><td>" + e.imagepath 
                                                + "</td><td>" + e.nom_fr + "</td><td>" + e.prenom_fr + "</td><td>" + e.nom_ar + "</td><td>" + e.prenom_ar + "</td></tr>";
                    });

                    $("#TableNum_ActeDouble").html(htmlDataTable);
                    initDataTable($('#dataTableNum_ActeDouble'));                            
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

                    $("#notif-Resultat-3").text(result[2].length);

                    // injection des données                             
                    htmlDataTable = "";    
                    result[2].forEach(e => {                            
                        htmlDataTable += "<tr><td>" + e.id_lot + "</td><td>" + e.id_acte +  "</td><td>" + e.num_acte + "</td><td>" + e.imagepath 
                                                + "</td><td>" + e.nom_fr + "</td><td>" + e.prenom_fr + "</td><td>" + e.nom_ar + "</td><td>" + e.prenom_ar + "</td></tr>";
                    });

                    $("#TableNumActeImagepath").html(htmlDataTable);
                    initDataTable($('#dataTableNumActeImagepath'));
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

                    $("#notif-Resultat-2").text(result[2].length);

                    // injection des données                             
                    htmlDataTable = "";    
                    result[2].forEach(e => {                            
                        htmlDataTable += "<tr><td>" + e.id_lot + "</td><td>" + e.id_acte +  "</td><td>" + e.num_acte + "</td><td>" + e.imagepath
                                                + "</td><td>" + e.nom_fr + "</td><td>" + e.prenom_fr + "</td><td>" + e.nom_ar + "</td><td>" + e.prenom_ar 
                                                + "</td><td class='text-center'> <a href='#' style='color:black;'> <i class='far fa-edit'></i> </a> </td></tr>";
                    });

                    $("#TableNumeroActeVide").html(htmlDataTable);
                    initDataTable($('#dataTableNumeroActeVide'));
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
            notifResultat.fadeOut("fast"); 
            ResultatData.fadeOut("fast");    

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
                            $("#liste-indic li:eq(0)").html("Traitement Image Vide : (" + result[1].length + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                            $("#liste-indic li:eq(0)").fadeIn(1000);
                            console.log('success : '  + result[1].length);

                            // paramétrage de l'affichage
                            notifResultat.fadeIn("slow");
                            ResultatData.fadeIn("slow");
                            $("#notif-Resultat-1").text(result[1].length);
                                                    
                            // injection des données                             
                            htmlDataTable = "";    
                            result[1].forEach(e => {                            
                               htmlDataTable += "<tr><td>" + e.id_lot + "</td><td>" + e.id_acte +  "</td><td>" + e.num_acte + "</td><td>" + e.imagepath
                                                        + "</td><td>" + e.nom_fr + "</td><td>" + e.prenom_fr + "</td><td>" + e.nom_ar + "</td><td>" + e.prenom_ar + "</td></tr>";
                            });

                            $("#TableImageVide").html(htmlDataTable);
                            initDataTable($('#dataTableImageVide'));
                            
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