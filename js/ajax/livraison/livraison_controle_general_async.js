$(document).ready(function() 
{
    var HostLink = window.location.href.split("/")[0] +"//"+ window.location.href.split("/")[2]+ "/" +window.location.href.split("/")[3];
    HostLink = HostLink.includes(".php") ? "./" : HostLink;

    // Selection des indicateurs 
    var btnControle = $("#btn-controle"),formLoader = $("#form-lot-loader");
    var txtNbLot = $("#txt-nb-lot");
    var txtControleNotif = $("#txt-nb-lot-notif")
    ,notifResultat = $("#notif-Resultat-bell"),
    indicTermine = $("#indic-termine")
    ,ResultatData = $("#resultat_data");
    $("#text-list-lot").val("");
    
    var listLotError = "";
    let TomeErrone_data,NumActeError_data,ExtDoublonImageLot_data,CorrPOPFmissing_data,ExtractVoid_data
        ,ExtLotCountError_data,CheckImagePathAndBdd_data,CorrDateControle_data,ChecksStat_data

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

    // Préparation des données à envoyer
    var countNbLot = function(txt) 
    {
        var txtArray =  txt.split("\n").filter(function(el) {return el.trim().length != 0});        
        return txtArray.length;
    };   

    $(".btn-form-modal-cancel").on("click",function()
    {
        e.preventDefault();
    });

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
                    $("#text-list-lot").val($("#text-list-lot").val() + '\n' + e.id_lot);                    
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
    get_id_lots();    
    
    
    function download(data_excel,fileName) 
    {        
       if(data_excel.length > 0)
       {
           $.post(HostLink+'/proccess/ajax/xlsx/generate.php',
           { myData: JSON.stringify(data_excel) },
           function(data, status, jqXHR)
           {              
               let result = JSON.parse(data);

               if(result[0] == "success")
               {
                    console.log(result[1]);
                    $("#download").attr("href",result[1]);
                    $("#download").attr("download",fileName);
                    $("#download")[0].click();
               }
           });
       } 
    }

    $("#TomeErrone_dl").on("click",function(e)
    {         
       download(TomeErrone_data,"CONTROLE_TOME_REGISTRE.xlsx");        
       e.preventDefault(); 
    });

    $("#NumActeError_dl").on("click",function(e)
    {         
       download(NumActeError_data,"NUM_ACTE_ERRONNE.xlsx");        
       e.preventDefault(); 
    });

    $("#ExtDoublonImageLot_dl").on("click",function(e)
    {         
       download(ExtDoublonImageLot_data,"DOUBLON_IMAGE.xlsx");        
       e.preventDefault(); 
    });

    $("#CorrPOPFmissing_dl").on("click",function(e)
    {         
       download(CorrPOPFmissing_data,"CONTROLE_PO_PF_MANQUANT.xlsx");        
       e.preventDefault(); 
    });

    $("#ExtractVoid_dl").on("click",function(e)
    {         
       download(ExtractVoid_data,"CONTROLE_NUM_ACTE_VIDE.xlsx");        
       e.preventDefault(); 
    });

    $("#ExtLotCountError_dl").on("click",function(e)
    {         
       download(ExtLotCountError_data,"NOMBRE_LOT_ERRONE.xlsx");        
       e.preventDefault(); 
    });

    $("#CheckImagePathAndBdd_dl").on("click",function(e)
    {         
       download(CheckImagePathAndBdd_data,"IMAGE_ACTE_NON_CORRESPONDANT.xlsx");        
       e.preventDefault(); 
    });

    $("#CorrDateControle_dl").on("click",function(e)
    {         
       download(CorrDateControle_data,"DATE_CONTROLE_ERRONES.xlsx");        
       e.preventDefault(); 
    });

    $("#ChecksStat_dl").on("click",function(e)
    {         
       download(ChecksStat_data,"COMPARAISON_INVENTAIRE.xlsx");        
       e.preventDefault(); 
    });
        
    // Traitement ExtractVoid
    var Corr3minControleEnd = function() { 
        $.get(HostLink+'/proccess/ajax/livraison/Corr3minControleEnd.php',            
            function(data, status, jqXHR) 
            {
                console.log(data);
                var result = JSON.parse(data);                               
                
                if(result[0] == "success")
                {
                    // success callback
                    // display result    
                    $("#liste-indic li:eq(9)").html("Contrôle 3 minutes: (" + result[1] + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                    $("#liste-indic li:eq(9)").fadeIn(1000);
                                        
                    $("#notif-Resultat-10").text(result[1]);
                    console.log('success : '  + result[1]);                    

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

    
    // Traitement ExtractVoid
    var ChecksStat = function() { 
        $.get(HostLink+'/proccess/ajax/livraison/ChecksStat.php',
            {async: false},
            function(data, status, jqXHR) 
            {
                console.log(data);
                var result = JSON.parse(data);                               
                
                if(result[0] == "success")
                {
                    // success callback
                    // display result    
                    $("#liste-indic li:eq(8)").html("Comparaison Inventaire : (" + result[1].length + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                    $("#liste-indic li:eq(8)").fadeIn(1000);
                                        
                    $("#notif-Resultat-9").text(result[1].length);
                    console.log('success : '  + result[1].length);
                    ChecksStat_data = result[1];
                    
                    // injection des données                             
                    htmlDataTable = "";    
                    result[1].forEach(e => {                            
                        htmlDataTable += "<tr id='ChecksStat"+ e.id_acte +"'>"
                                            +'<td > '+ e.id_lot + '</td>'
                                            +'<td > '+ e.nb_acte_bd + '</td>'
                                            +'<td > '+ e.nb_acte_inv + '</td>'
                                            +'<td > '+ e.nb_ctrl1_bd + '</td>'
                                            +'<td > '+ e.nb_ctrl1_inv + '</td>'                                            
                                            +'<td > '+ e.nb_ctrl2_bd + '</td>'                                             
                                            +'<td > '+ e.nb_ctrl2_inv + '</td>'                                             
                                            +'<td > '+ e.observation + '</td>'                                             
                                        +"</tr>";

                        // Ajout de l'IdLot dans les erronés
                        if(!listLotError.includes(e.id_lot))
                        {
                            listLotError += e.id_lot + "\n"; 
                        }
                    });
                        
                    $("#dataTableChecksStat").dataTable().fnDestroy(); 
                    $("#TableChecksStat").html(htmlDataTable);
                    initDataTable($('#dataTableChecksStat')); 
                }
                else
                {
                    console.log('message error : ' + result);
                    console.log(result);
                } 
            }
        );   
    } 
    
    // Traitement ExtractVoid
    var CorrDateControle = function() { 
        $.get(HostLink+'/proccess/ajax/livraison/CorrDateControle.php',
            {async: false},
            function(data, status, jqXHR) 
            {
                console.log(data);
                var result = JSON.parse(data);                               
                
                if(result[0] == "success")
                {
                    // success callback
                    // display result    
                    $("#liste-indic li:eq(7)").html("Correct. Date Controle : (" + result[1].length + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                    $("#liste-indic li:eq(7)").fadeIn(1000);
                                        
                    $("#notif-Resultat-8").text(result[1].length);
                    console.log('success : '  + result[1].length);
                    CorrDateControle_data = result[1];
                    
                    // injection des données                             
                    htmlDataTable = "";    
                    result[1].forEach(e => {                            
                        htmlDataTable += "<tr id='CorrDateControle"+ e.id_acte +"'>"
                                            +'<td > '+ e.id_lot + '</td>'
                                            +'<td > '+ e.ctrl1_last + '</td>'
                                            +'<td > '+ e.ctrl2_first + '</td>'
                                            +'<td > '+ e.nb_ligne_modif + '</td>'                                            
                                            +'<td > '+ e.observation + '</td>'                                             
                                        +"</tr>";

                        // Ajout de l'IdLot dans les erronés
                        if(!listLotError.includes(e.id_lot))
                        {
                            listLotError += e.id_lot + "\n"; 
                        }
                    });
                        
                    $("#dataTableCorrDateControle").dataTable().fnDestroy(); 
                    $("#TableCorrDateControle").html(htmlDataTable);
                    initDataTable($('#dataTableCorrDateControle')); 
                }
                else
                {
                    console.log('message error : ' + result);
                    console.log(result);
                } 
            }
        );   
    } 
    
    // Traitement ExtractVoid
    var CheckImagePathAndBdd = function() { 
        $.get(HostLink+'/proccess/ajax/livraison/CheckImagePathAndBdd.php',
            {async: false},
            function(data, status, jqXHR) 
            {
                console.log(data);
                var result = JSON.parse(data);                               
                
                if(result[0] == "success")
                {
                    // success callback
                    // display result    
                    $("#liste-indic li:eq(6)").html("Verif. ImagePath : (" + result[1].length + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                    $("#liste-indic li:eq(6)").fadeIn(1000);
                                        
                    $("#notif-Resultat-7").text(result[1].length);
                    console.log('success : '  + result[1].length);
                    CheckImagePathAndBdd_data = result[1];
                    
                    // injection des données                             
                    htmlDataTable = "";    
                    result[1].forEach(e => {                            
                        htmlDataTable += "<tr id='CheckImagePathAndBdd"+ e.id_acte +"'>"
                                            +'<td > '+ e.id_lot + '</td>'
                                            +'<td > '+ e.id_acte + '</td>'
                                            +'<td > '+ e.num_acte + '</td>'
                                            +'<td > '+ e.imagepath + '</td>'                                            
                                            +'<td > '+ e.chemin_dossier + '</td>'                                            
                                            +'<td > '+ e.observation + '</td>'                                            
                                        +"</tr>";

                        // Ajout de l'IdLot dans les erronés
                        if(!listLotError.includes(e.id_lot))
                        {
                            listLotError += e.id_lot + "\n"; 
                        }
                    });
                        
                    $("#dataTableCheckImagePathAndBdd").dataTable().fnDestroy(); 
                    $("#TableCheckImagePathAndBdd").html(htmlDataTable);
                    initDataTable($('#dataTableCheckImagePathAndBdd')); 
                }
                else
                {
                    console.log('message error : ' + result);
                    console.log(result);
                } 
            }
        );   
    } 
    
    // Traitement ExtractVoid
    var ExtLotCountError = function() { 
        $.get(HostLink+'/proccess/ajax/livraison/ExtLotCountError.php',
            {async: false},
            function(data, status, jqXHR) 
            {
                console.log(data);
                var result = JSON.parse(data);                               
                
                if(result[0] == "success")
                {
                    // success callback
                    // display result    
                    $("#liste-indic li:eq(5)").html("Compte Lot erroné : (" + result[1].length + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                    $("#liste-indic li:eq(5)").fadeIn(1000);
                                        
                    $("#notif-Resultat-6").text(result[1].length);
                    console.log('success : '  + result[1].length);
                    ExtLotCountError_data = result[1];
                    
                    // injection des données                             
                    htmlDataTable = "";    
                    result[1].forEach(e => {                            
                        htmlDataTable += "<tr id='ExtLotCountError"+ e.id_acte +"'>"
                                            +'<td > '+ e.id_lot + '</td>'
                                            +'<td > '+ e.nb_image_inv + '</td>'
                                            +'<td > '+ e.nb_image_db + '</td>'
                                            +'<td > '+ e.nb_image_repos + '</td>'                                            
                                            +'<td > '+ e.chemin_lot + '</td>'                                            
                                            +'<td > '+ e.observation + '</td>'                                            
                                        +"</tr>";

                        // Ajout de l'IdLot dans les erronés
                        if(!listLotError.includes(e.id_lot))
                        {
                            listLotError += e.id_lot + "\n"; 
                        }
                    });
                        
                    $("#dataTableExtLotCountError").dataTable().fnDestroy(); 
                    $("#TableExtLotCountError").html(htmlDataTable);
                    initDataTable($('#dataTableExtLotCountError')); 
                }
                else
                {
                    console.log('message error : ' + result);
                    console.log(result);
                } 
            }
        );   
    }   

    // Traitement ExtractVoid
    var ExtractVoid = function() { 
        $.get(HostLink+'/proccess/ajax/livraison/ExtractVoid.php',
            {async: false},
            function(data, status, jqXHR) 
            {
                console.log(data);
                var result = JSON.parse(data);                               
                
                if(result[0] == "success")
                {
                    // success callback
                    // display result    
                    $("#liste-indic li:eq(4)").html("Extraction Vide : (" + result[1].length + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                    $("#liste-indic li:eq(4)").fadeIn(1000);
                                        
                    $("#notif-Resultat-5").text(result[1].length);
                    console.log('success : '  + result[1].length);
                    ExtractVoid_data = result[1];
                    
                    // injection des données                             
                    htmlDataTable = "";    
                    result[1].forEach(e => {                            
                        htmlDataTable += "<tr id='ExtractVoid"+ e.id_acte +"'>"
                                            +'<td > '+ e.id_lot + '</td>'
                                            +'<td > '+ e.id_acte + '</td>'
                                            +'<td > '+ e.num_acte + '</td>'
                                            +'<td > '+ e.id_tome_registre + '</td>'                                            
                                            +'<td > '+ e.status_acte + '</td>'                                            
                                        +"</tr>";

                        // Ajout de l'IdLot dans les erronés
                        if(!listLotError.includes(e.id_lot))
                        {
                            listLotError += e.id_lot + "\n"; 
                        }
                    });
                        
                    $("#dataTableExtractVoid").dataTable().fnDestroy(); 
                    $("#TableExtractVoid").html(htmlDataTable);
                    initDataTable($('#dataTableExtExtractVoid')); 
                }
                else
                {
                    console.log('message error : ' + result);
                    console.log(result);
                } 
            }
        );   
    }   

    // Traitement TomeRegistre/Registre
    var CorrPOPFmissing = function() { 
        $.get(HostLink+'/proccess/ajax/livraison/CorrPOPFmissing.php',
            {async: false},
            function(data, status, jqXHR) 
            {
                console.log(data);
                var result = JSON.parse(data);                               
                
                if(result[0] == "success")
                {
                    // success callback
                    // display result    
                    $("#liste-indic li:eq(3)").html("Correction PO/PF : (" + result[1].length + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                    $("#liste-indic li:eq(3)").fadeIn(1000);
                                        
                    $("#notif-Resultat-4").text(result[1].length);
                    console.log('success : '  + result[1].length);
                    CorrPOPFmissing_data = result[1];
                    
                    // injection des données                             
                    htmlDataTable = "";    
                    result[1].forEach(e => {                            
                        htmlDataTable += "<tr id='CorrectionPOPFRow"+ e.id_acte +"'>"
                                            +'<td > '+ e.id_lot + '</td>'
                                            +'<td > '+ e.nb_po + '</td>'
                                            +'<td > '+ e.nb_pf + '</td>'
                                            +'<td > '+ e.chemin_lot + '</td>'                                            
                                        +"</tr>";

                        // Ajout de l'IdLot dans les erronés
                        if(!listLotError.includes(e.id_lot))
                        {
                            listLotError += e.id_lot + "\n"; 
                        }
                    });
                        
                    $("#dataTableExtCorrPOPFmissing").dataTable().fnDestroy(); 
                    $("#TableCorrPOPFmissing").html(htmlDataTable);
                    initDataTable($('#dataTableExtCorrPOPFmissing')); 
                }
                else
                {
                    console.log('message error : ' + result);
                    console.log(result);
                } 
            }
        );   
    }   

    // Traitement TomeRegistre/Registre
    var ExtDoublonImageLot = function() { 
        $.get(HostLink+'/proccess/ajax/livraison/ExtDoublonImageLot.php',
            {async: false},
            function(data, status, jqXHR) 
            {
                console.log(data);
                var result = JSON.parse(data);                               
                
                if(result[0] == "success")
                {
                    // success callback
                    // display result    
                    $("#liste-indic li:eq(2)").html("Doublon Images : (" + result[1].length + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                    $("#liste-indic li:eq(2)").fadeIn(1000);
                                        
                    $("#notif-Resultat-3").text(result[1].length);
                    console.log('success : '  + result[1].length);
                    ExtDoublonImageLot_data = result[1];
                    
                    // injection des données                             
                    htmlDataTable = "";    
                    result[1].forEach(e => {                            
                        htmlDataTable += "<tr id='DoublonImageLotRow"+ e.id_acte +"'>"
                                            +'<td > '+ e.id_lot + '</td>'
                                            +'<td > '+ e.id_acte + '</td>'
                                            +'<td > '+ e.num_acte + '</td>'
                                            +'<td > '+ e.imagepath + '</td>'
                                            +'<td > '+ e.observation + '</td>'                                            
                                        +"</tr>";

                        // Ajout de l'IdLot dans les erronés
                        if(!listLotError.includes(e.id_lot))
                        {
                            listLotError += e.id_lot + "\n"; 
                        }
                    });
                        
                    $("#dataTableExtDoublonImageLot").dataTable().fnDestroy(); 
                    $("#TableExtDoublonImageLot").html(htmlDataTable);
                    initDataTable($('#dataTableExtDoublonImageLot')); 
                }
                else
                {
                    console.log('message error : ' + result);
                    console.log(result);
                } 
            }
        );   
    }   

    // Traitement TomeRegistre/Registre
    var ExtractNumActeError = function() { 
        $.get(HostLink+'/proccess/ajax/livraison/ExtractNumActeError.php',
            {async: false},
            function(data, status, jqXHR) 
            {
                console.log(data);
                var result = JSON.parse(data);                               
                
                if(result[0] == "success")
                {
                    // success callback
                    // display result    
                    $("#liste-indic li:eq(1)").html("Num_acte Erroné : (" + result[1].length + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                    $("#liste-indic li:eq(1)").fadeIn(1000);
                                        
                    $("#notif-Resultat-2").text(result[1].length);
                    console.log('success : '  + result[1].length);
                    NumActeError_data = result[1];
                    
                    // injection des données                             
                    htmlDataTable = "";    
                    result[1].forEach(e => {                            
                        htmlDataTable += "<tr id='NumActeErrorRow"+ e.id_acte +"'>"
                                            +'<td > '+ e.id_lot+'</td>'
                                            +'<td > '+ e.id_acte+'</td>'
                                            +'<td > '+ e.old_num_acte +'</td>'
                                            +'<td > '+ e.num_acte +'</td>'
                                            +'<td > '+ e.imagepath +'</td>'
                                            +'<td > '+ e.observation +'</td>'                                            
                                        +"</tr>";

                        // Ajout de l'IdLot dans les erronés
                        if(!listLotError.includes(e.id_lot))
                        {
                            listLotError += e.id_lot + "\n"; 
                        }
                    });
                        
                    $("#dataTableNum_acteError").dataTable().fnDestroy(); 
                    $("#TableNum_acteError").html(htmlDataTable);
                    initDataTable($('#dataTableNum_acteError'));  
                }
                else
                {
                    console.log('message error : ' + result);
                    console.log(result);
                } 
            }
        );   
    }

    // Traitement TomeRegistre/Registre
    var ExtractNumActeError = function() { 
        $.get(HostLink+'/proccess/ajax/livraison/ExtractNumActeError.php',
            {async: false},
            function(data, status, jqXHR) 
            {
                console.log(data);
                var result = JSON.parse(data);                               
                
                if(result[0] == "success")
                {
                    // success callback
                    // display result    
                    $("#liste-indic li:eq(1)").html("Num_acte Erroné : (" + result[1].length + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                    $("#liste-indic li:eq(1)").fadeIn(1000);
                                        
                    $("#notif-Resultat-2").text(result[1].length);
                    console.log('success : '  + result[1].length);
                    NumActeError_data = result[1];
                    
                    // injection des données                             
                    htmlDataTable = "";    
                    result[1].forEach(e => {                            
                        htmlDataTable += "<tr id='NumActeErrorRow"+ e.id_acte +"'>"
                                            +'<td > '+ e.id_lot+'</td>'
                                            +'<td > '+ e.id_acte+'</td>'
                                            +'<td > '+ e.old_num_acte +'</td>'
                                            +'<td > '+ e.num_acte +'</td>'
                                            +'<td > '+ e.imagepath +'</td>'
                                            +'<td > '+ e.observation +'</td>'                                            
                                        +"</tr>";

                        // Ajout de l'IdLot dans les erronés
                        if(!listLotError.includes(e.id_lot))
                        {
                            listLotError += e.id_lot + "\n"; 
                        }
                    });
                        
                    $("#dataTableNum_acteError").dataTable().fnDestroy(); 
                    $("#TableNum_acteError").html(htmlDataTable);
                    initDataTable($('#dataTableNum_acteError'));   
                }
                else
                {
                    console.log('message error : ' + result);
                    console.log(result);
                } 
            }
        );   
    }

    var CorrectionTome = function() 
    {
        $.get(HostLink+'/proccess/ajax/livraison/correction_tome.php',   // url           
            {async: false},
            function(data, status, jqXHR) 
            {     
                console.log(data);
                result = JSON.parse(data);                                            
                if(result[0] == "success")
                {                        
                    // success callback
                    // display result    
                    $("#liste-indic li:eq(0)").html("Correction Tome : (" + result[1].length + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                    $("#liste-indic li:eq(0)").fadeIn(1000);
                    console.log('success : '  + result[1].length);

                    // paramétrage de l'affichage
                    notifResultat.fadeIn("slow");
                    ResultatData.fadeIn("slow");
                    $("#notif-Resultat-1").text(result[1].length);

                    TomeErrone_data = result[1];
                                            
                    // injection des données                             
                    htmlDataTable = "";    
                    result[1].forEach(e => {                            
                        htmlDataTable += "<tr id='TomeCorrRow"+ e.id_tome_registre +"'>"
                                            +'<td > '+ e.id_lot+'</td>'
                                            +'<td > '+ e.id_tome_actu+'</td>'
                                            +'<td > '+ e.id_tome_corr +'</td>'
                                            +'<td > '+ e.chemin_lot +'</td>'
                                            +'<td > '+ e.observation +'</td>'
                                        +"</tr>";

                        // Ajout de l'IdLot dans les erronés
                        if(!listLotError.includes(e.id_lot))
                        {
                            listLotError += e.id_lot + "\n"; 
                        }
                    });
                    
                    $("#dataTableTomeErrone").dataTable().fnDestroy();
                    $("#TableTomeErrone").html(htmlDataTable);
                    initDataTable($('#dataTableTomeErrone'));                                                                                       
                }
                else
                {
                    console.log('message error : ' + result[1]);
                    console.log(result);
                }
            }
        ).fail(function(res){
            console.log("fail");
            console.log(res);
        });  
    }

    btnControle.on('click',function(e)
    {
        var nbLot = countNbLot($("#text-list-lot").val());            
        e.preventDefault();        
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

            // Correction tome 
            CorrectionTome();
            // Lancement du Traitement Numéro Acte vide 
            ExtractNumActeError();     
            // Lancement du Traitement Numéro Acte vide 
            ExtDoublonImageLot();      
            // Lancement du Traitement Numéro Acte vide 
            ExtDoublonImageLot();     
            // Lancement du Traitement CorrPOPFmissing
            CorrPOPFmissing();      
            // Lancement du Traitement CorrPOPFmissing
            ExtractVoid();                     
            // Lancement du Traitement CorrPOPFmissing
            ExtLotCountError();  
            // Terminé le Lancement
            CheckImagePathAndBdd();                      
            // Terminé le Lancement
            CorrDateControle();    
            // Terminé le Lancement
            ChecksStat();                    
            // Terminé le Lancement
            Corr3minControleEnd();
        }
    });
})