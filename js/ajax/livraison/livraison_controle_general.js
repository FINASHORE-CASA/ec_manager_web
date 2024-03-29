$(document).ready(function() 
{
    
    var HostLink = window.location.href.split("/")[0] +"//"+ window.location.href.split("/")[2]+ "/" +window.location.href.split("/")[3];
    HostLink = HostLink.includes(".php") ? "." : HostLink;

    // Selection des indicateurs 
    var btnControle = $("#btn-controle"),formLoader = $("#form-lot-loader");
    var txtNbLot = $("#txt-nb-lot");
    var txtControleNotif = $("#txt-nb-lot-notif")
    ,notifResultat = $("#notif-Resultat-bell"),
    indicTermine = $("#indic-termine")
    ,ResultatData = $("#resultat_data");    
    $("#text-list-lot").val("");
    var bd_name = $("#bd_name_link>span").text().trim().toUpperCase();
 
    var listLotError = "";
    let TomeErrone_data,NumActeError_data,ExtDoublonImageLot_data,CorrPOPFmissing_data,ExtractVoid_data
        ,ExtLotCountError_data,CheckImagePathAndBdd_data,CorrDateControle_data,ChecksStat_data
        ,Iscollectif_data,ControleMariageDivorce_data

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
                    $("#text-list-lot").val($("#text-list-lot").val() + e.id_lot + '\n' );                    
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
       download(TomeErrone_data,"REJETS_" + bd_name+ "_CONTROLE_TOME_REGISTRE.xlsx");        
       e.preventDefault(); 
    });

    $("#NumActeError_dl").on("click",function(e)
    {         
       download(NumActeError_data,"REJETS_" + bd_name+ "_NUM_ACTE_ERRONNE.xlsx");        
       e.preventDefault(); 
    });

    $("#ExtDoublonImageLot_dl").on("click",function(e)
    {         
       download(ExtDoublonImageLot_data,"REJETS_" + bd_name+ "_DOUBLON_IMAGE.xlsx");        
       e.preventDefault(); 
    });

    $("#CorrPOPFmissing_dl").on("click",function(e)
    {         
       download(CorrPOPFmissing_data,"REJETS_" + bd_name+ "_CONTROLE_PO_PF_MANQUANT.xlsx");        
       e.preventDefault(); 
    });

    $("#ExtractVoid_dl").on("click",function(e)
    {         
       download(ExtractVoid_data,"REJETS_" + bd_name+ "_CONTROLE_NUM_ACTE_VIDE.xlsx");        
       e.preventDefault(); 
    });

    $("#ExtLotCountError_dl").on("click",function(e)
    {         
       download(ExtLotCountError_data,"REJETS_" + bd_name+ "_NOMBRE_LOT_ERRONE.xlsx");        
       e.preventDefault(); 
    });

    $("#CheckImagePathAndBdd_dl").on("click",function(e)
    {         
       download(CheckImagePathAndBdd_data,"REJETS_" + bd_name+ "_IMAGE_ACTE_NON_CORRESPONDANT.xlsx");        
       e.preventDefault(); 
    });

    $("#CorrDateControle_dl").on("click",function(e)
    {         
       download(CorrDateControle_data,"REJETS_" + bd_name+ "_DATE_CONTROLE_ERRONES.xlsx");        
       e.preventDefault(); 
    });

    $("#ChecksStat_dl").on("click",function(e)
    {         
       download(ChecksStat_data,"REJETS_" + bd_name+ "_COMPARAISON_INVENTAIRE.xlsx");        
       e.preventDefault(); 
    });

    $("#ControleIsCollectif_dl").on("click",function(e)
    {
       download(Iscollectif_data,"REJETS_" + bd_name+ "_.xlsx");        
       e.preventDefault(); 
    });

    $("#ControleMariageDivorce_dl").on("click",function(e)
    {
       download(ControleMariageDivorce_data,"REJETS_" + bd_name+ "_.xlsx");        
       e.preventDefault();
    });

    $("#text-list-lot-livre").on("keyup",function(e) 
    {
        var nbLot = countNbLot($(this).val());
        $("#list-notif-idlot-livre").text(nbLot);
    });    

    var ControleMariageDivorce = function(){
        $.get(HostLink + '/proccess/ajax/livraison/ControleMariageDivorce.php',
            function (data, status, jqXHR) {
                console.log(data);
                var result = JSON.parse(data);

                if (result[0] == "success") {
                    // success callback
                    // display result    
                    $("#liste-indic li:eq(11)").html("Contrôle Mariage/Divorce : (" + result[1].length + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                    $("#liste-indic li:eq(11)").fadeIn(1000);
                    $("#notif-Resultat-12").text(result[1].length);
                    ControleMariageDivorce_data = result[1];

                    // injection des données  
                    htmlDataTable = "";
                    result[1].forEach(e => {
                        htmlDataTable += "<tr id='ControleMariageDivorce" + e.id_lot + "'>"
                            + '<td > ' + e.id_lot + '</td>'
                            + '<td > ' + e.id_acte + '</td>'
                            + '<td > ' + e.num_acte + '</td>'
                            + '<td > ' + e.id_collectif + '</td>'
                            + "</tr>";

                        // Ajout de l'IdLot dans les erronés
                        if (!listLotError.includes(e.id_lot)) {
                            listLotError += e.id_lot + "\n";
                        }
                    });

                    $("#dataTableControleMariageDivorce").dataTable().fnDestroy();
                    $("#TableControleMariageDivorce").html(htmlDataTable);
                    initDataTable($('#dataTableControleMariageDivorce'));

                    // Terminé le Lancement
                    $("#text-list-lot-errone").val(listLotError);
                    $("#indic-lot-error").text(countNbLot(listLotError));
                    formLoader.fadeOut("slow");
                    indicTermine.fadeIn(3000);
                }

                else {
                    console.log('message error : ' + result);
                    console.log(result);
                }
            }
        );
    }

    var ControleIsCollectif = () => {
        $.get(HostLink + '/proccess/ajax/livraison/ControleIsCollectif.php',
            function (data, status, jqXHR) {
                console.log(data);
                var result = JSON.parse(data);

                if (result[0] == "success") {
                    // success callback
                    // display result    
                    $("#liste-indic li:eq(10)").html("Contrôle is collectif : (" + result[1].length + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                    $("#liste-indic li:eq(10)").fadeIn(1000);
                    $("#notif-Resultat-11").text(result[1].length);
                    Iscollectif_data = result[1];

                    // injection des données  
                    htmlDataTable = "";
                    result[1].forEach(e => {
                        htmlDataTable += "<tr id='ControleIsCollectif" + e.id_lot + "'>"
                            + '<td > ' + e.id_lot + '</td>'
                            + '<td > ' + e.id_acte + '</td>'
                            + '<td > ' + e.num_acte + '</td>'
                            + '<td > ' + e.id_collectif + '</td>'
                            + "</tr>";

                        // Ajout de l'IdLot dans les erronés
                        if (!listLotError.includes(e.id_lot)) {
                            listLotError += e.id_lot + "\n";
                        }
                    });

                    $("#dataTableControleIsCollectif").dataTable().fnDestroy();
                    $("#TableControleIsCollectif").html(htmlDataTable);
                    initDataTable($('#dataTableControleIsCollectif'));

                    // Terminé le Lancement
                    ControleMariageDivorce();
                }

                else {
                    console.log('message error : ' + result);
                    console.log(result);
                }
            }
        );
    }
        
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
                    $("#liste-indic li:eq(9)").html("Contrôle 3 minutes : (" + result[1] + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                    $("#liste-indic li:eq(9)").fadeIn(1000);
                                        
                    $("#notif-Resultat-10").text(result[1]);
                    console.log('success : '  + result[1]);

                    // Terminé le Lancement
                    ControleIsCollectif();
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
                                            +'<td > '+ e.nb_naiss_bd + '</td>'
                                            +'<td > '+ e.nb_naiss_inv + '</td>'
                                            +'<td > '+ e.nb_mariage_bd + '</td>'
                                            +'<td > '+ e.nb_mariage_inv + '</td>'
                                            +'<td > '+ e.nb_divorce_bd + '</td>'
                                            +'<td > '+ e.nb_divorce_inv + '</td>'
                                            +'<td > '+ e.nb_deces_bd + '</td>'
                                            +'<td > '+ e.nb_deces_inv + '</td>'
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

                    // Terminé le Lancement
                    Corr3minControleEnd();
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

                    // Terminé le Lancement
                    ChecksStat(); 
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

                    // Terminé le Lancement
                    CorrDateControle();
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
                        htmlDataTable += "<tr id='ExtLotCountError"+ e.id_lot +"'>"
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

                    // Terminé le Lancement
                    CheckImagePathAndBdd();  
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

                    // Lancement du Traitement CorrPOPFmissing
                    ExtLotCountError(); 
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

                    // Lancement du Traitement CorrPOPFmissing
                    ExtractVoid(); 
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

                    // Lancement du Traitement CorrPOPFmissing
                    CorrPOPFmissing(); 
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

                    // Lancement du Traitement Numéro Acte vide 
                    ExtDoublonImageLot();      
                }
                else
                {
                    console.log('message error : ' + result);
                    console.log(result);
                } 
            }
        );   
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
            $("#text-list-lot-errone").val("");
            $("#indic-lot-error").text(0);
            listLotError = "";
            

            // transfert lot 
            $.get(HostLink+'/proccess/ajax/livraison/correction_tome.php',   // url            
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
                            
                        // Lancement du Traitement Numéro Acte vide 
                        ExtractNumActeError();      
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
    });
})