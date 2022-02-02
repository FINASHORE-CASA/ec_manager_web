$(document).ready(function() 
{    
    var HostLink = window.location.href.split("/")[0] +"//"+ window.location.href.split("/")[2]+ "/" +window.location.href.split("/")[3];
    HostLink = HostLink.includes(".php") ? "." : HostLink;

    // Selection des indicateurs 
    var btnControle = $("#btn-controle")
        ,formLoader = $("#form-lot-loader");
    var textListLot = $("#text-list-lot");
    var txtNbLot = $("#txt-nb-lot");
    var txtControleNotif = $("#txt-nb-lot-notif")
    ,indicTermine = $("#indic-termine")
    ,notifResultat = $("#notif-Resultat-bell")
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

    $('#dataTableVerifImage').on('draw.dt', function () {
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
        $("#field-PrenomFr").val("");                                                                                                                      
        $("#field-PrenomAr").val("");                                                                                                                      
        $("#field-Genre").val("");                                                                                                                       
        $("#field-NomFr").val("");                                                                                                                      
        $("#field-NomAr").val("");                                                                                                                      
        $("#field-PrenomMargeFr").val("");                                                                                                                      
        $("#field-PrenomMargeAr").val("");                                                                                                                          
        $("#field-jour_g").val("");                                                                                                                          
        $("#field-mois_g").val("");                   
        $("#field-annee_g").val("");                   
        $("#field-jour_h").val("");                   
        $("#field-mois_h").val("");                   
        $("#field-annee_h").val("");                      
        $("#img-block").html("<p class='d-flex justify-content-center' style='margin-top:250px;'> Image Introuvable </p>");         
    });
    
    $("#form-update-save").on("click",function(e){            
        // Récupération de l'Id du click
        var data1 = {
            id_acte: $("#field-IdActe").val(),
            prenom_fr:$("#field-PrenomFr").val().trim(),
            prenom_ar:$("#field-PrenomAr").val().trim(),
            sexe:$("#field-Genre").val().trim(),
            nom_fr:$("#field-NomFr").val().trim(),
            nom_ar:$("#field-NomAr").val().trim(),
            nom_marge_fr:$("#field-NomMargeFr").val().trim(),            
            nom_marge_ar:$("#field-NomMargeAr").val().trim(),            
            prenom_marge_fr:$("#field-PrenomMargeFr").val().trim(),            
            prenom_marge_ar:$("#field-PrenomMargeAr").val().trim(),
            jd_naissance_g:$("#field-jour_g").val().trim(),            
            md_naissance_g:$("#field-mois_g").val().trim(),            
            ad_naissance_g:$("#field-annee_g").val().trim(),            
            jd_naissance_h:$("#field-jour_h").val().trim(),            
            md_naissance_h:$("#field-mois_h").val().trim(),            
            ad_naissance_h:$("#field-annee_h").val().trim()            
        }   

        $.post(HostLink+'/proccess/ajax/saisi/update_acte_identite.php',   // url
        { myData: JSON.stringify(data1) }, // data to be submit
            function(data, status, jqXHR) 
            {
                var result = JSON.parse(data);                               
                
                if(result[0] == "success")
                {
                    // success callback
                    // display result    
                    console.log('success : ' + result[1]);
                    if(result[1])
                    {
                        $("#ActeRow"+data1.id_acte).fadeOut();
                        $(".btn-form-modal-cancel").trigger("click");                        
                        show_alert("success","Modification effectuée ");
                        $("#notif-Resultat-1").text(parseInt($("#notif-Resultat-1").text()) - 1);
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
        btnEdit.on("click",function(e){

            // Lancement du chargement des information
            // $("#form-acte-field").css("display","none");
            $("#form-group1").css("background","none")
            $("#form-group2").css("background","none")
            $("#form-acte-loader").css("display","block");

            // Récupération de l'Id du click
            var data1 = {
                id_acte: $(this).attr("idActe"),
            }     

            let typeError =  $(this).attr("type-error");
            let erroColor = "#c22b3040";
            let isGroup1 = true;
            
            $("#field-NomMargeFr").css("background","none") 
            $("#field-PrenomMargeFr").css("background","none")             
            $("#field-NomMargeAr").css("background","none") 
            $("#field-PrenomMargeAr").css("background","none") 
            $("#field-jour_g").css("background","none") 
            $("#field-mois_g").css("background","none") 
            $("#field-annee_g").css("background","none") 
            $("#field-jour_h").css("background","none") 
            $("#field-mois_h").css("background","none") 
            $("#field-annee_h").css("background","none")  

            if($(this).attr("nom_mg_fr_s") == "1")
            {
                $("#field-NomMargeFr").css("background",erroColor) ;
            }

            if($(this).attr("prenom_mg_fr_s") == "1")
            {
                $("#field-PrenomMargeFr").css("background",erroColor)  
            }

            if($(this).attr("nom_mg_ar_s") == "1")
            {
                $("#field-NomMargeAr").css("background",erroColor)       
            }

            if($(this).attr("prenom_mg_ar_s") == "1")
            {
                $("#field-PrenomMargeAr").css("background",erroColor)                
            }

            if($(this).attr("jn_g_s") == "1")
            {
                $("#field-jour_g").css("background",erroColor)  
            }

            if($(this).attr("mn_g_s") == "1")
            {
                $("#field-mois_g").css("background",erroColor) 
            }

            if($(this).attr("an_g_s") == "1")
            {
                $("#field-annee_g").css("background",erroColor) 
            }

            if($(this).attr("jn_h_s") == "1")
            {
                $("#field-jour_h").css("background",erroColor)
            }

            if($(this).attr("mn_h_s") == "1")
            {
                $("#field-mois_h").css("background",erroColor)   
            }

            if($(this).attr("an_h_s") == "1")
            {
                $("#field-annee_h").css("background",erroColor)  
            }
                
            if($(this).attr("prenom_nofound") == "1")
            {
                $("#form-group1").css("background",erroColor);
            }

            $.post(HostLink+'/proccess/ajax/action_auto/recup_acte_identite.php',   // url
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
                        $("#field-PrenomFr").val(result[1].prenom_fr);                                                                                                                      
                        $("#field-PrenomAr").val(result[1].prenom_ar);                                                                                                                      
                        $("#field-NomFr").val(result[1].nom_fr);                                                                                                                      
                        $("#field-NomAr").val(result[1].nom_ar);                                                                                                                      
                        $("#field-PrenomMargeFr").val(result[1].prenom_marge_fr);                                                                                                                      
                        $("#field-PrenomMargeAr").val(result[1].prenom_marge_ar);                                                                                                                          
                        $("#field-NomMargeFr").val(result[1].nom_marge_fr);                                                                                                                          
                        $("#field-NomMargeAr").val(result[1].nom_marge_ar);                                                                                                                          
                        $("#field-Genre").val(result[1].sexe);                                                                                                                          
                        $("#field-jour_g").val(result[1].jd_naissance_g);                                                                                                                          
                        $("#field-mois_g").val(result[1].md_naissance_g);                   
                        $("#field-annee_g").val(result[1].ad_naissance_g);                   
                        $("#field-jour_h").val(result[1].jd_naissance_h);                   
                        $("#field-mois_h").val(result[1].md_naissance_h);                   
                        $("#field-annee_h").val(result[1].ad_naissance_h);                                            

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
                                                         +  "<a id='img-switch2' class='img-switch' href='#' ownid='2' style='color: gray;font-size:20px;text-decoration:none;'> <i class='far fa-dot-circle ml-1'></i></a>");
                            }
                            else
                            {
                                $("#img-block").html("<img id='image1' class='img-fluid img-thumbnail' style='height:auto;width:auto;' src='"+ result[3] +"\\" + result[1].id_acte + "_" + result[1].imagepath +"' alt='"+  result[1].imagepath +"'/>");                                                 
                                $("#block-img-change").html("<a id='img-switch1' class='img-switch' href='#' ownid='1' style='color: black;font-size:20px;text-decoration:none;'> <i class='far fa-dot-circle'></i></a>");
                            }
                        }           
                        else
                        {
                            $("#img-block").html("<p class='d-flex justify-content-center' style='margin-top:250px;'> Image Introuvable </p>");
                        }

                        startSwitchImage();
                    }
                    else
                    {
                        console.log('message error : ' + result);
                        console.log(result);
                    } 
                    
                    // $("#form-acte-field").css("display","block");
                    $("#form-acte-loader").css("display","none");
                }
            ).fail(function()
            { 
                $("#form-acte-field").css("display","inherit");
                $("#form-acte-field").css("display","none");
                alert("erreur lors de l'execution");
            }); 
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
                    $("#text-list-lot").val($("#text-list-lot").val() + e.id_lot + '\n');                    
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

    $("#show_all").on("click",function(){

        if($(this)[0].checked == true)
        {
            textListLot.val("");
            textListLot.attr("disabled","true");
            
            // Recherche des actes concernés            
            get_id_lots(); 
        }
        else
        {
            textListLot.removeAttr("disabled");
        }
    })
    $("#show_all")[0].checked = false;

    $("#btn-add-champs-prenom-genre").on("click",function() 
    {                
        // Récupération de l'Id du click
        var data1 = {
            id_user:$("#field-Id_user").val(),
            prenom_fr:$("#field-PrenomFr").val().trim(),
            prenom_ar:$("#field-PrenomAr").val().trim(),           
            genre_prenom:$("#field-Genre").val().trim(),
        }    

        // add identite bd 
        $.post(HostLink+'/proccess/ajax/saisi/add_identite_bd.php',
        { myData: JSON.stringify(data1) }, // data to be submit
            function(data, status, jqXHR) 
            {
                console.log(data);
                var result = JSON.parse(data);                               
                
                if(result[0] == "success")
                {
                    // display result
                    alert (" informations ajoutés ")    
                }
                else if(result[0] == "exist")
                {
                    alert (" ces informations existent déjà ")    
                }                
                else
                {
                    alert(" une erreur s'est produite");
                    console.log(' message error : ' + result);
                    console.log(result);
                }
            }
        ); 
    });


    btnControle.on('click',function(e)
    {
        var nbLot = countNbLot(textListLot.val());
        listLotError = "";
        $("#text-list-lot-errone").val("");
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
            $.post(HostLink+'/proccess/ajax/saisi/controle_identitaire.php',   // url
                { myData: JSON.stringify(data1) }, // data to be submit
                    function(data, status, jqXHR) 
                    {
                        console.log(data);
                        var result = JSON.parse(data);                               
                        
                        if(result[0] == "success")
                        {
                            // success callback
                            // display result    
                            $("#liste-indic li:eq(0)").html("Vérification des identités : (" + result[1].length + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                            $("#liste-indic li:eq(0)").fadeIn(1000);
                            console.log('success : '  + result[1].length);

                            // paramétrage de l'affichage
                            notifResultat.fadeIn("slow");
                            ResultatData.fadeIn("slow");
                            $("#notif-Resultat-1").text(result[1].length);
                                                    
                            // injection des données                             
                            htmlDataTable = "";    
                            result[1].forEach(e => {   
                                
                                htmlDataTable += "<tr id='ActeRow"+ e.id_acte +"'>"
                                            +"<td class='text-center'> <a href='#' class='btn-edit' idActe='"+ e.id_acte +"' style='color:gray;' data-toggle='modal' data-target='#ActeModal' type-error='"
                                            + e.marge_erreur +"' " 
                                            +"nom_mg_fr_s='"+e.nom_mg_fr_s+"' "
                                            +"prenom_mg_fr_s='"+e.prenom_mg_fr_s+"' "
                                            +"nom_mg_ar_s='"+e.nom_mg_ar_s+"'"
                                            +"prenom_mg_ar_s='"+e.prenom_mg_ar_s+"' "
                                            +"jn_g_s='"+e.jn_g_s+"' "
                                            +"mn_g_s='"+e.mn_g_s+"' "
                                            +"an_g_s='"+e.an_g_s+"' "
                                            +"jn_h_s='"+e.jn_h_s+"' "
                                            +"mn_h_s='"+e.mn_h_s+"' "
                                            +"an_h_s='"+e.an_h_s+"' "
                                            +"prenom_nofound='"+e.prenom_nofound+"' "
                                            +">"                                             
                                            +"<i class='fas fa-highlighter'></i></a> </td>"
                                            +"<td>" + e.id_lot + "</td><td>" + e.id_acte +  "</td><td>" + e.nom_fr 
                                            +"</td><td>" + e.nom_ar + "</td><td>" + e.nom_marge_fr + "</td><td>" + e.nom_marge_ar + "</td><td>" + e.prenom_fr + "</td>"
                                            +"</td><td>" + e.prenom_ar + "</td><td>" + e.prenom_marge_fr + "</td><td>" + e.prenom_marge_ar + "</td><td>" + e.sexe + "</td>"
                                            +"<td>" + e.jd_naissance_g + "</td><td>" + e.md_naissance_g + "</td><td>" + e.ad_naissance_g + "</td>"
                                            +"<td>" + e.jd_naissance_h + "</td><td>" + e.md_naissance_h + "</td><td>" + e.ad_naissance_h + "</td>"
                                            +"</td></tr>";
                                
                                // Ajout de l'IdLot dans les erronés
                                if(!listLotError.includes(e.id_lot))
                                {
                                    listLotError += e.id_lot + "\n"; 
                                }
                            });
                            
                            $("#dataTableVerifImage").dataTable().fnDestroy()
                            $("#TableVerifImage").html(htmlDataTable);
                            initDataTable($('#dataTableVerifImage'));                                              
                                                        
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
        e.preventDefault();        
    });
});