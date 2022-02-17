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

        setTimeout(() => { $("#alert-container #alert_box").fadeOut("slow");} , (time*1000));                                
    }  

    $('#dataTableRecupPoPf').on('draw.dt', function () {
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
        $("#field-id_lot").text("");

        $("#HeaderTableRecupPoPf tr th").each(function(i,th) 
        {
            if(th.getAttribute("name").toLowerCase() != "" && th.getAttribute("name").toLowerCase() != "modif")
            {
                $("#field-"+th.getAttribute("name")).val("");
            }                                                            
        });

        $("#img-block").html("");         
    });
    
    $("#form-update-save").on("click",function(e){            
        // Récupération de l'Id du click
        var data1 = {}   

        $("#HeaderTableRecupPoPf tr th").each(function(i,th) 
        {   
            if(th.getAttribute("name").toLowerCase() != "" && th.getAttribute("name").toLowerCase() != "modif")
            {
                data1[th.getAttribute("name")] = $("#field-"+th.getAttribute("name")).val().trim();
            }                                                            
        });

        console.log(data1);

        $.post(HostLink+'/proccess/ajax/controleOecPoPf/update_tome_po_pf.php',   // url
        { myData: JSON.stringify(data1) }, // data to be submit
            function(data, status, jqXHR) 
            {
                var result = JSON.parse(data);   
                console.log(result);
                
                if(result[0] == "success")
                {
                    if(result[1] == "1")
                    {
                        // success callback
                        // display result    
                        console.log('success : ' + result[1]);
                        $("#TomeRow"+data1.id_tome_registre).fadeOut();
                        $(".btn-form-modal-cancel").trigger("click");                        
                        show_alert("success","Modification effectuée ");
                        $("#notif-Resultat-1").text(parseInt($("#notif-Resultat-1").text()) - 1);                    
                    }
                    else
                    {                        
                        $(".btn-form-modal-cancel").trigger("click");                        
                        show_alert("danger","Erreur lors de la modification","le SGBD retoune false",10);
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
            $("#form-acte-loader").css("display","inherit");

            // Récupération de l'Id du click
            var data1 = {
                id_tome_registre: $(this).attr("idTome"),
            }     

            $.post(HostLink+'/proccess/ajax/controleOecPoPf/recup_tome_po_pf.php',   // url
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
                        for (const key in result[1]) 
                        {
                            if (Object.hasOwnProperty.call(result[1], key)) 
                            {
                                $("#field-"+key).val(result[1][key]);                          
                            }
                        }
                        $("#field-id_lot").text(result[1].id_lot)
                        
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
                                        htmlContentImg += "<img id='image"+ i + "' class='img-fluid img-thumbnail' style='height:auto;width:auto;"+ ((i == 2) ? "display:none;" : "") +"' src='"+ result[3] +"\\" + result[1].id_lot + "_" +  e + "' alt='"+  e +"'/>";                 
                                    }  
                                    i++;
                                });
                                $("#img-block").html(htmlContentImg);
                                $("#block-img-change").html("<a id='img-switch1' class='img-switch' href='#' ownid='1' style='color: black;font-size:20px;text-decoration:none;'> <i class='far fa-dot-circle'></i></a>"
                                                         +  "<a id='img-switch2' class='img-switch' href='#' ownid='2' style='color: gray;font-size:20px;text-decoration:none;'> <i class='far fa-dot-circle ml-1'></i></a>" 
                                                         +  "<a id='img-zoom-reset' class='ml-2' href='#' ownid='2' style='color: black;font-size:20px;text-decoration:none;'> <i class='fas fa-dice-one'></i></a>");
                            }
                            else
                            {
                                $("#img-block").html("<img id='image1' class='img-fluid img-thumbnail' style='height:auto;width:auto;' src='"+ result[3] +"\\" + result[1].id_lot + "_" + result[1].imagepath +"' alt='"+  result[1].imagepath +"'/>");                                                 
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
                            resetButton.addEventListener('click', panzoom.reset);                            
                        }           
                        else
                        {
                            $("#img-block").html("");
                            $("#block-img-change").html("");
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

    function reduceText(txt,limit)
    {
        return txt ? (txt.length > limit ? txt.substr(0,limit -3) + '...' : txt) : "";
    }

    btnControle.on('click',function(e)
    {
        var nbLot = countNbLot(textListLot.val());
        listLotError = "";
        $("#text-list-lot-errone").val("");
        e.preventDefault();        
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
            $.post(HostLink+'/proccess/ajax/controleOecPoPf/recup_list_popf.php',   // url
                { myData: JSON.stringify(data1) }, // data to be submit
                    function(data, status, jqXHR) 
                    {
                        console.log(data);
                        var result = JSON.parse(data);                               
                        
                        if(result[0] == "success")
                        {
                            // success callback
                            // display result    
                            $("#liste-indic li:eq(0)").html("Récupération des PO PF : (" + result[1].length + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                            $("#liste-indic li:eq(0)").fadeIn(1000);
                            console.log('success : '  + result[1].length);

                            // paramétrage de l'affichage
                            notifResultat.fadeIn("slow");
                            ResultatData.fadeIn("slow");
                            $("#notif-Resultat-1").text(result[1].length); 
                                                    
                            // injection des données                             
                            htmlDataTable = "";    
                            result[1].forEach(e => 
                            {   
                                htmlDataTable += "<tr id='TomeRow"+ e.id_tome_registre +"'>"
                                                +"<td class='text-center'> <a href='#' class='btn-edit' idTome='"+ e.id_tome_registre +"' style='color:gray;' data-toggle='modal' data-target='#TomeModal' >"                                             
                                                +"<i class='fas fa-highlighter'></i></a> </td>"                                                                                               
                                $("#HeaderTableRecupPoPf tr th").each(function(i,th) 
                                {
                                    for (const key in e) 
                                    {
                                        if (Object.hasOwnProperty.call(e, key)) 
                                        {
                                            if(key.toLowerCase() == th.getAttribute("name").toLowerCase())
                                            {
                                                htmlDataTable += "<td>" + reduceText(e[key],50) + "</td>"
                                            }                                            
                                        }
                                    }
                                });
                                htmlDataTable += "</tr>"; 
                            });

                            $("#dataTableRecupPoPf").dataTable().fnDestroy()
                            $("#TableRecupPoPf").html(htmlDataTable);
                            initDataTable($('#dataTableRecupPoPf'));                                              
                                                        
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
    });
});