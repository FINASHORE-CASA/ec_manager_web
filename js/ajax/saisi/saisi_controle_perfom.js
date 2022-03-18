$(document).ready(function() 
{    
    var HostLink = window.location.href.split("/")[0] +"//"+ window.location.href.split("/")[2]+ "/" +window.location.href.split("/")[3];
    HostLink = HostLink.includes(".php") ? "." : HostLink;

    // Selection des indicateurs 
    var btnControle = $("#btn-controle")
        ,formLoader = $("#form-lot-loader");
    var textListLot = $("#text-list-lot");
    var txtNbLot = $("#txt-nb-lot");
    var txtControleNotif = $("#txt-nb-lot-notif"),
    indicTermine = $("#indic-termine"),notifResultat = $("#notif-Resultat-bell")
    ,ResultatData = $("#resultat_data");
    textListLot.val("");
    
    var listLotError = "";

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

    $('#dataTableNumeroActeVide,#dataTableNumActeImagepath,#dataTableNum_ActeDouble,#dataTableImageSaisitDouble').on('draw.dt', function () {
        startEditActe();                           
    });

    var TableRowInsertion = function(data)
    {
        htmlDataTable = "";    
        data.forEach(e => 
        {                            
            htmlDataTable += "<tr id='ActeRow"+ e.id_acte +"'><td>" + e.id_lot + "</td><td>" + e.id_acte +  "</td><td>" + e.num_acte + "</td><td>" + e.imagepath
                                    + "</td><td>" + e.nom_fr + "</td><td>" + e.prenom_fr + "</td><td>" + e.nom_ar + "</td><td>" + e.prenom_ar 
                                    + "</td><td class='text-center'> <a href='#' class='btn-edit' idActe='"+ e.id_acte +"' style='color:gray;' data-toggle='modal' data-target='#ActeModal'>" 
                                    + "<i class='fas fa-highlighter'></i></a> </td></tr>";

            // Ajout de l'IdLot dans les erronés
            if(!listLotError.includes(e.id_lot))
            {
                listLotError += e.id_lot + "\n"; 
            }
        });
        
        return htmlDataTable;
    };

    $(".btn-form-modal-cancel").on("click", function(e) {
        // Rétablissement des champs du formulaire
        $("#field-IdLot").val("");                                                                                                                      
        $("#field-IdActe").val("");                                                                                                                      
        $("#field-NumActe").val("");                                                                                                                      
        $("#field-Imagepath").val("");                                                                                                                      
        $("#field-NomFr").val("");                                                                                                                      
        $("#field-PrenomFr").val("");                                                                                                                      
        $("#field-NomAr").val("");                                                                                                                      
        $("#field-PrenomAr").val("");   
        $("#img-block").html("<p class='d-flex justify-content-center' style='margin-top:250px;'> Image Introuvable </p>");         
    });
    
    $("#form-update-save").on("click",function(e){            
        // Récupération de l'Id du click
        var data1 = {
            id_acte: $("#field-IdActe").val(),
            num_acte:$("#field-NumActe").val()
        }   

        $.post(HostLink+'/proccess/ajax/saisi/update_acte.php',   // url
        { myData: JSON.stringify(data1) }, // data to be submit
            function(data, status, jqXHR) 
            {
                var result = JSON.parse(data);                               
                
                if(result[0] == "success")
                {
                    // success callback
                    // display result    
                    console.log('success : ' + result[1] );
                    if( result[1] == '1')
                    {
                        $("#ActeRow"+data1.id_acte + " td:eq(2)").html(data1.num_acte);
                        $("#ActeModal").modal("hide");                    
                        show_alert("success","Modification effectuée");
                        $(".btn-form-modal-cancel").trigger("click");
                    }                    
                }
                else
                {
                    show_alert("danger","erreur lors de la modification",result,10);
                    console.log('message error : ' + result);
                    console.log(result);
                }
            }
        ); 

    });  

    var startSwitchImage = function() 
    {
        $(".img-switch").on("click",function(e)
        {
            e.preventDefault();
            if($(this).attr("ownid") == "1")
            {
                if($("#image1").css("display") == "none")
                {
                   $("#image2").fadeOut(); 
                   var src = $("#image1").attr("src");
                   $("#image1").removeAttr("src").attr("src",src);                   
                   $("#image1").fadeIn(2000); 
                   $("#image2").css("display","none")
                   $(this).css("color","black");
                   $("#img-switch2").css("color","gray");
                }                
            }      
            else
            {
                if($("#image2").css("display") == "none")
                {
                   $("#image1").fadeOut(); 
                   var src = $("#image2").attr("src");
                   $("#image2").removeAttr("src").attr("src",src);  
                   $("#image2").fadeIn(2000); 
                   $("#image1").css("display","none")
                   $(this).css("color","black");
                   $("#img-switch1").css("color","gray");
                }    
            }      
        });
    };

    //function de modification de l'acte
    var startEditActe = function(e) {
        var btnEdit = $(".btn-edit");
        

        // Remplissage des informations de l'acte
        btnEdit.on("click",function(e)
        {            
            $("#form-acte-loader").css("display","block");
            // Récupération de l'Id du click
            var data1 = {
                id_acte: $(this).attr("idActe"),
            }     

            $.post(HostLink+'/proccess/ajax/saisi/controle_recup_acte.php',   // url
                { myData: JSON.stringify(data1) }, // data to be submit
                function(data, status, jqXHR) 
                {
                    console.log(data);
                    var result = JSON.parse(data);                               
                    
                    if(result[0] == "success")
                    {
                        // success callback
                        // display result   
                        
                        // injection des données
                        console.log(result[0]);
                        console.log(result[1]); 
                        console.log(result[2]); 
                        console.log(result[3]); 

                        // Remplissage des champs du formulaire
                        $("#field-IdLot").val(result[1].id_lot);                                                                                                                      
                        $("#field-IdActe").val(result[1].id_acte);                                                                                                                      
                        $("#field-NumActe").val(result[1].num_acte);                                                                                                                      
                        $("#field-Imagepath").val(result[1].imagepath);                                                                                                                      
                        $("#field-NomFr").val(result[1].nom_fr);                                                                                                                      
                        $("#field-PrenomFr").val(result[1].prenom_fr);                                                                                                                      
                        $("#field-NomAr").val(result[1].nom_ar);                                                                                                                      
                        $("#field-PrenomAr").val(result[1].prenom_ar);              

                        if(result[2] == "yes")
                        {
                            $ImageTab = result[1].imagepath.split(";;");

                            if(result[1].imagepath.includes(";;"))
                            {
                                var i = 1;
                                var htmlContentImg = "";
                                $ImageTab.forEach((e) => {
                                    if(e.trim() != "")
                                    {
                                        htmlContentImg += "<img id='image"+ i + "' class='img-fluid img-thumbnail' style='height:auto;width:auto;"+ ((i == 2) ? "display:none;" : "") +"' src='"+ result[3] +"\\" + result[1].id_acte + "_" +  e + "' alt='"+  e +"'/>";                 
                                    }  
                                    i++;
                                });
                                $("#img-block").html(htmlContentImg);
                                $("#block-img-change").html("<a id='img-switch1' class='img-switch' href='#' ownid='1' style='color: black;font-size:20px;text-decoration:none;'> <i class='far fa-dot-circle'></i></a>"
                                                           +"<a id='img-switch2' class='img-switch' href='#' ownid='2' style='color: gray;font-size:20px;text-decoration:none;'> <i class='far fa-dot-circle ml-1'></i></a>"
                                                           +"<a id='img-zoom-reset' class='ml-2' href='#' ownid='2' style='color: black;font-size:20px;text-decoration:none;'> <i class='fas fa-dice-one'></i></a>");
                            }
                            else
                            {
                                $("#img-block").html("<img id='image1' class='img-fluid img-thumbnail' style='height:auto;width:auto;' src='"+ result[3] +"\\" + result[1].id_acte + "_" + result[1].imagepath +"' alt='"+  result[1].imagepath +"'/>");                                                 
                                $("#block-img-change").html("<a id='img-switch1' class='img-switch' href='#' ownid='1' style='color: black;font-size:20px;text-decoration:none;'> <i class='far fa-dot-circle'></i></a>"
                                                            +"<a id='img-zoom-reset' class='ml-2' href='#' ownid='2' style='color: black;font-size:20px;text-decoration:none;'> <i class='fas fa-dice-one'></i></a>");
                            }
                        
                            // initialisation du plugin de zoom                                                 
                            const element = document.getElementById('img-block')
                            const resetButton = document.getElementById('img-zoom-reset');
                            const panzoom = Panzoom(element, {
                                // options here
                            });
                            // enable mouse wheel
                            const parent = element.parentElement
                            parent.addEventListener('wheel', panzoom.zoomWithWheel);
                            parent.addEventListener('click', panzoom.reset);
                        }           
                        else
                        {
                            $("#img-block").html("<p class='d-flex justify-content-center' style='margin-top:250px;'> Image Introuvable </p>");
                        }

                        startSwitchImage();
                        $("#form-acte-loader").css("display","none");
                    }
                    else
                    {
                        console.log('message error : ' + result);
                        console.log(result);
                    } 
                }
            ); 
        });
    }

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
            $.post(HostLink+'/proccess/ajax/saisi/controle_image_double.php',   // url
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
                    $("#dataTableImageSaisitDouble").dataTable().fnDestroy();
                    $("#TableImageSaisitDouble").html(TableRowInsertion(result[2]));
                    initDataTable($('#dataTableImageSaisitDouble'));   
                                
                    // Terminé le Lancement
                    $("#text-list-lot-errone").val(listLotError);
                    $("#indic-lot-error").text(countNbLot(listLotError));
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
            $.post(HostLink+'/proccess/ajax/saisi/controle_num_acte_double.php',   // url
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
                    $("#dataTableNum_ActeDouble").dataTable().fnDestroy();
                    $("#TableNum_ActeDouble").html(TableRowInsertion(result[2]));
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
        $.post(HostLink+'/proccess/ajax/saisi/controle_num_acte_diff_imagepath.php',   // url
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
                    $("#dataTableNumActeImagepath").dataTable().fnDestroy()
                    $("#TableNumActeImagepath").html(TableRowInsertion(result[2]));
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
        $.post(HostLink+'/proccess/ajax/saisi/controle_num_acte_vide.php',   // url
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
                    $("#dataTableNumeroActeVide").dataTable().fnDestroy()
                    $("#TableNumeroActeVide").html(TableRowInsertion(result[2]));                    
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
        var nbLot = countNbLot(textListLot.val());
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
            $.post(HostLink+'/proccess/ajax/saisi/controle_image_vide.php',   // url
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

                                // Ajout de l'IdLot dans les erronés
                                if(!listLotError.includes(e.id_lot))
                                {
                                    listLotError += e.id_lot + "\n"; 
                                }
                            });
                            
                            $("#dataTableImageVide").dataTable().fnDestroy()
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