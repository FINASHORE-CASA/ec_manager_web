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
    
    let comp_inv_db_inventaire;
    let comp_inv_db_tomeregistre;
    var listLotError = "";

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

    $("#text-list-lot-livre").on("keyup",function(e) 
    {
        var nbLot = countNbLot($(this).val());
        $("#list-notif-idlot-livre").text(nbLot);
    });   

    // Traitement TomeRegistre/Registre
    var traitementTomeRegistre = function() { 
        $.get(HostLink+'/proccess/ajax/livraison/comp_inv_db_tomeregistre.php',
            function(data, status, jqXHR) 
            {
                console.log(data);
                var result = JSON.parse(data);                               
                
                if(result[0] == "success")
                {
                    // Enregistrement des données 
                    comp_inv_db_tomeregistre = result[1];

                    
                    // success callback
                    // display result    
                    $("#liste-indic li:eq(1)").html("Traitement TomeRegistre : (" + result[1].length + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                    $("#liste-indic li:eq(1)").fadeIn(1000);
                                        
                    $("#notif-Resultat-2").text(result[1].length);
                    console.log('success : '  + result[1].length);
                    
                    // injection des données                             
                    htmlDataTable = "";    
                    result[1].forEach(e => {                            
                        htmlDataTable += "<tr id='TomeRegistreRow"+ e.id_tome_registre +"'>"
                                            +'<td > '+ e.id_lot+'</td>'
                                            +'<td > '+ e.indice_db+'</td>'
                                            +'<td > '+ e.indice_inv +'</td>'
                                            +'<td > '+ e.tome_db +'</td>'
                                            +'<td > '+ e.tome_inv +'</td>'
                                            +'<td > '+ e.annee_g_db +'</td>'
                                            +'<td > '+ e.annee_g_inv +'</td>'
                                            +'<td > '+ e.annee_h_db +'</td>'
                                            +'<td > '+ e.annee_h_inv +'</td>'
                                            +'<td > '+ e.naissance_db +'</td>'
                                            +'<td > '+ e.naissance_inv +'</td>'
                                            +'<td > '+ e.deces_db +'</td>'
                                            +'<td > '+ e.deces_inv +'</td>'
                                            +'<td > '+ e.acte_db +'</td>'
                                            +'<td > '+ e.acte_inv +'</td>'
                                        +"</tr>";

                        // Ajout de l'IdLot dans les erronés
                        if(!listLotError.includes(e.id_lot))
                        {
                            listLotError += e.id_lot + "\n"; 
                        }
                    });
                        
                    $("#dataTableTomeRegistre").dataTable().fnDestroy(); 
                    $("#TableTomeRegistre").html(htmlDataTable);
                    initDataTable($('#dataTableTomeRegistre')); 

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

    $("#Inventaire_db_dl").on("click",function(e)
    {         
       download(comp_inv_db_inventaire,"COMAPARAISON_INVENTAIRE_INVENTAIRE_DB.xlsx");        
       e.preventDefault(); 
    });

    $("#TomeRegistre_dl").on("click",function(e)
    {
       download(comp_inv_db_tomeregistre,"COMAPARAISON_INVENTAIRE_TOME_REGISTRE.xlsx");        
       e.preventDefault(); 
    });

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
            // transfert lot 
            $.get(HostLink+'/proccess/ajax/livraison/comp_inv_db_inventaire.php',   // url            
                function(data, status, jqXHR) 
                {     
                    console.log(data);
                    result = JSON.parse(data);                                            
                    if(result[0] == "success")
                    {       
                        // Enregistrement des données 
                        comp_inv_db_inventaire = result[1];

                        // success callback
                        // display result    
                        $("#liste-indic li:eq(0)").html("Traitement Table Inventaire_db : (" + result[1].length + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                        $("#liste-indic li:eq(0)").fadeIn(1000);
                        console.log('success : '  + result[1].length);

                        // paramétrage de l'affichage
                        notifResultat.fadeIn("slow");
                        ResultatData.fadeIn("slow");
                        $("#notif-Resultat-1").text(result[1].length);
                                                
                        // injection des données                             
                        htmlDataTable = "";    
                        result[1].forEach(e => {                            
                            htmlDataTable += "<tr id='InventaireDbRow"+ e.id_inventaire +"'>"
                                                +'<td > '+ e.id_lot+'</td>'
                                                +'<td > '+ e.indice_db+'</td>'
                                                +'<td > '+ e.indice_inv +'</td>'
                                                +'<td > '+ e.tome_db +'</td>'
                                                +'<td > '+ e.tome_inv +'</td>'
                                                +'<td > '+ e.annee_g_db +'</td>'
                                                +'<td > '+ e.annee_g_inv +'</td>'
                                                +'<td > '+ e.annee_h_db +'</td>'
                                                +'<td > '+ e.annee_h_inv +'</td>'
                                                +'<td > '+ e.naissance_db +'</td>'
                                                +'<td > '+ e.naissance_inv +'</td>'
                                                +'<td > '+ e.deces_db +'</td>'
                                                +'<td > '+ e.deces_inv +'</td>'
                                                +'<td > '+ e.acte_db +'</td>'
                                                +'<td > '+ e.acte_inv +'</td>'
                                            +"</tr>";

                            // Ajout de l'IdLot dans les erronés
                            if(!listLotError.includes(e.id_lot))
                            {
                                listLotError += e.id_lot + "\n"; 
                            }
                        });
                        
                        $("#dataTableInventaire_db").dataTable().fnDestroy();
                        $("#TableInventaire_db").html(htmlDataTable);
                        initDataTable($('#dataTableInventaire_db'));                                             
                            
                        // Lancement du Traitement Numéro Acte vide 
                        traitementTomeRegistre();      
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