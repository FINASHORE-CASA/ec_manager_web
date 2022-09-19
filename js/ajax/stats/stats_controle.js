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
    var acteInfo = [];
    
    var listLotError = "";
    let controle_unitaire_db;
    let controle_consultation_unitaire_db;
    let mention_manquant_db;

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

    $("#StatsControleUnitaire_dl").on("click",function(e)
    {         
       download(controle_unitaire_db,"STATS_CONTROLE_UNITAIRE.xlsx");        
       e.preventDefault(); 
    });

    $("#StatsControleUnitaireConsult_dl").on("click",function(e)
    {         
       download(controle_consultation_unitaire_db,"STATS_CONSULTATION_CONTROLE_UNITAIRE.xlsx");        
       e.preventDefault(); 
    });

    $("#StatsMentionManquant_dl").on("click",function(e)
    {         
       download(mention_manquant_db,"STATS_MENTION_MANQUANT.xlsx");        
       e.preventDefault(); 
    });

    function stats_mention_manquant(data1)
    { 
        $.post(HostLink+'/proccess/ajax/stats/stats_mention_manquant.php',   // url
        { myData: JSON.stringify(data1) }, // data to be submit
            function(data, status, jqXHR) 
            {                    
                var result = JSON.parse(data);  
                console.log(result);
                
                if(result[0] == "success")
                {
                    // success callback
                    // display result    
                    $("#liste-indic li:eq(2)").html(" Stats Mention Manquants : (" + result[1].length + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                    $("#liste-indic li:eq(2)").fadeIn(1000);
                    console.log('success : '  + result[1].length);

                    // paramétrage de l'affichage
                    notifResultat.fadeIn("slow");
                    ResultatData.fadeIn("slow");
                    $("#notif-Resultat-3").text(result[1].length);
                    mention_manquant_db = result[1];
                                            
                    // injection des données                             
                    htmlDataTable = "";    
                    result[1].forEach(e => {   
                        
                        htmlDataTable += "<tr>"
                                            + "<th> "+ e.id_lot +" </th>"
                                            + "<th> "+ e.id_acte +" </th>"
                                            + "<th> "+ e.mention_acte +" </th>"
                                            + "<th> "+ e.mention_corr +" </th>"
                                            + "<th> "+ e.date_cont +" </th>"
                                            + "<th> "+ e.login +" </th>"
                                         +"</tr>"
                    });
                    
                    $("#dataTableStatsManquantMention").dataTable().fnDestroy()
                    $("#TableStatsManquantMention").html(htmlDataTable);
                    initDataTable($('#dataTableStatsManquantMention'));                                              
                                                
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

    function stats_consult_controle_unitaire(data1)
    {     
        $.post(HostLink+'/proccess/ajax/stats/stats_controle_unitaire_consult.php',   // url
        { myData: JSON.stringify(data1) }, // data to be submit
            function(data, status, jqXHR) 
            {                          
                var result = JSON.parse(data);  
                console.log(result);
                
                if(result[0] == "success")
                {
                    // success callback
                    // display result    
                    $("#liste-indic li:eq(1)").html(" Stats Consult Contrôle Unitaire : (" + result[1].length + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                    $("#liste-indic li:eq(1)").fadeIn(1000);
                    console.log('success : '  + result[1].length);

                    // paramétrage de l'affichage
                    notifResultat.fadeIn("slow");
                    ResultatData.fadeIn("slow");
                    $("#notif-Resultat-2").text(result[1].length);
                    controle_consultation_unitaire_db = result[1];
                                            
                    // injection des données                             
                    htmlDataTable = "";    
                    result[1].forEach(e => {   
                        
                        htmlDataTable += "<tr>"
                                        + "<th> "+ e.id_lot +" </th>"
                                        + "<th> "+ e.login +" </th>"
                                        // + "<th> "+ e.nb_acte +" </th>"
                                        + "<th> "+ e.nb_acte_ctr +" </th>"
                                        + "<th> "+ e.date_ctr +" </th>"
                                        +"</tr>"
                    });
                    
                    $("#dataTableStatsControleUnitaireConsult").dataTable().fnDestroy()
                    $("#TableStatsControleUnitaireConsult").html(htmlDataTable);
                    initDataTable($('#dataTableStatsControleUnitaireConsult'));                                                                                                                                      
                }
                else
                {
                    console.log('message error : ' + result);
                    console.log(result);
                }
                
                stats_mention_manquant(data1)  
            }
        );  
    }

    function stats_controle_unitaire(data1)
    {     
        $.post(HostLink+'/proccess/ajax/stats/stats_controle_unitaire.php',   // url
        { myData: JSON.stringify(data1) }, // data to be submit
            function(data, status, jqXHR) 
            {                          
                var result = JSON.parse(data);  
                console.log(result);
                
                if(result[0] == "success")
                {
                    // success callback
                    // display result    
                    $("#liste-indic li:eq(0)").html(" Stats Contrôle Unitaire : (" + result[1].length + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                    $("#liste-indic li:eq(0)").fadeIn(1000);
                    console.log('success : '  + result[1].length);

                    // paramétrage de l'affichage
                    notifResultat.fadeIn("slow");
                    ResultatData.fadeIn("slow");
                    $("#notif-Resultat-1").text(result[1].length);
                    controle_unitaire_db = result[1];
                                            
                    // injection des données                             
                    htmlDataTable = "";    
                    result[1].forEach(e => {   
                        
                        htmlDataTable += "<tr>"
                                        + "<th> "+ e.id_lot +" </th>"
                                        + "<th> "+ e.login +" </th>"
                                        // + "<th> "+ e.nb_acte +" </th>"
                                        + "<th> "+ e.nb_acte_ctr +" </th>"
                                        + "<th> "+ e.date_ctr +" </th>"
                                        +"</tr>"
                    });
                    
                    $("#dataTableStatsControleUnitaire").dataTable().fnDestroy()
                    $("#TableStatsControleUnitaire").html(htmlDataTable);
                    initDataTable($('#dataTableStatsControleUnitaire'));                                                                                                                                      
                }
                else
                {
                    console.log('message error : ' + result);
                    console.log(result);
                }
                
                stats_consult_controle_unitaire(data1)  
            }
        );  
    }

    btnControle.on('click',function(e)
    {
        e.preventDefault();   
        if($("#date_gen_deb_acte").val() != "" && $("#date_gen_fin_acte").val() != "")
        {
            // hide previous result
            $("#liste-indic li").fadeOut("slow");
            indicTermine.fadeOut("slow");
            formLoader.css("display","inherit");
            notifResultat.fadeOut("fast"); 
            ResultatData.fadeOut("fast");    

            // traitement des lots             
            var data1 = {
                date_debut: $("#date_gen_deb_acte").val(),
                date_fin: $("#date_gen_fin_acte").val()
            }                    
            stats_controle_unitaire(data1)
        }
        else
        {
            alert("les dates ne peuvent être vide")
        }
    });
});