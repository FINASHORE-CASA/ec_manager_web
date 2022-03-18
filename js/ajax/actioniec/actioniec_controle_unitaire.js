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
    ,ResultatData = $("#resultat_data")
    ,selectBoxListChamps = $("#list_champs");

    var acteInfo = [];
    var listLotError = "";
    textListLot.val("");

    var ListeActes = [1,2,3];

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
    
    $(".form-update-save").on("click",function(e)
    {            
        var data1 = {}   

        $("#ActeModal .form-control").each((i,el) => 
        {
            data1[el.id.replace("field-","")] = el.value.trim();            
        });            

        $.post(HostLink+'/proccess/ajax/actioniec/update_acte_unitaire.php',   // url
        { myData: JSON.stringify(data1) }, // data to be submit
            function(data, status, jqXHR) 
            {
                var result = JSON.parse(data);    

                if(result[0] == "success")
                {                    
                    // success callback
                    if(typeof result[1] === 'number')
                    {
                        result[5].forEach(nomchamp => 
                        {
                            if(Object.keys(data1).some(e1=> {return e1 == nomchamp['cname']}))
                            {
                            let elValue =  Object.keys(data1).filter(e1=> {return e1 == nomchamp['cname']})[0];                                       
                            $("#ActeRow"+data1.id_acte+ " td[name='"+[elValue]+"']").html(data1[elValue]);   
                            }
                        });                             

                        $("#ActeModal").modal("hide");                        
                        $(".btn-form-modal-cancel").trigger("click");
                        $("#ActeRow"+data1.id_acte).fadeOut("slow");   
                        // mis en place de l'alert 
                        show_alert("success","Modification effectué !","",3);
                    }                    
                    else
                    {
                        //Alert
                        $("#ActeModal").modal("hide");                        
                        $(".btn-form-modal-cancel").trigger("click");
                        // mis en place de l'alert 
                        show_alert("danger","Erreur lors de la Modification","",10);
                    }
                }
                else
                {
                    // Alert
                    $("#ActeModal").modal("hide");                        
                    $(".btn-form-modal-cancel").trigger("click");
                    // mis en place de l'alert 
                    show_alert("danger","Erreur lors de la Modification","type erreur : not successful",10);
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

                    $(".img-switch1").each((i,e) =>  
                    {                        
                        e.style.color = "black";
                    })

                    $(".img-switch2").each((i,e) =>  
                    {                        
                        e.style.color = "gray";
                    })
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

                    $(".img-switch1").each((i,e) =>  
                    {      
                        e.style.color = "gray";
                    })
                    $(".img-switch2").each((i,e) =>  
                    {
                        e.style.color = "black";     
                    })
                }    
            }      
        });
    };

    //function de modification de l'acte
    var startEditActe = function(e) 
    {
        var btnEdit = $(".btn-edit");

        // Remplissage des informations de l'acte
        btnEdit.on("click",function(e){
                        
            // Lancement du chargement des information
            $("#form-acte-loader").css("display","block");
            e.preventDefault()

            // Récupération de l'Id du click
            var data1 = {
                id_acte: $(this).attr("idActe"),
                id_lot:$(this).attr("id_lot"),
                imagepath:$(this).attr("imagepath")
            }     
            acteInfo = [];
                              
            let listTd = $("#ActeRow"+data1.id_acte+" td");                                  
            listTd.each((i,td) => 
            {
                $(`#field-${td.getAttribute("name")}`).val(td.innerHTML.trim()); 
            })
         
            $.post(HostLink+'/proccess/ajax/actioniec/recup_acte_image.php',   // url
                { myData: JSON.stringify(data1) }, // data to be submit
                function(data, status, jqXHR) 
                {
                    var result = JSON.parse(data);                                                                       
                    if(result[0] == "success")
                    {                    
                        // Remplissage des champs du formulaire  
                        acteInfo = result[1];                                     

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
                                        htmlContentImg += "<img id='image"+ i + "' class='img-fluid img-thumbnail' style='margin-left:60px;height:600px;width:auto;"+ ((i == 2) ? "display:none;" : "") +"' src='"+ result[3] +"\\" + result[1].id_acte + "_" +  e + "' alt='"+  e +"'/>";                 
                                    }  
                                    i++;
                                });
                                $("#img-block").html(htmlContentImg);
                                $(".block-img-change").html("<a class='img-switch img-switch1' href='#' ownid='1' style='color: black;font-size:20px;text-decoration:none;'> <i class='far fa-dot-circle'></i></a>"
                                                            +"<a class='img-switch img-switch2' href='#' ownid='2' style='color: gray;font-size:20px;text-decoration:none;'> <i class='far fa-dot-circle ml-1'></i></a>"
                                                            +"<a class='ml-2 img-zoom-reset' href='#' style='color: black;font-size:20px;text-decoration:none;'> <i class='fas fa-dice-one'></i></a>");
                            }
                            else
                            {
                                $("#img-block").html("<img id='image1' class='img-fluid img-thumbnail' style='height:auto;width:auto;' src='"+ result[3] +"\\" + result[1].id_acte + "_" + result[1].imagepath +"' alt='"+  result[1].imagepath +"'/>");                                                 
                                $(".block-img-change").html("<a class='img-switch img-switch1' href='#' ownid='1' style='color: black;font-size:20px;text-decoration:none;'> <i class='far fa-dot-circle'></i></a>"
                                                           +"<a class='ml-2 img-zoom-reset' href='#' style='color: black;font-size:20px;text-decoration:none;'> <i class='fas fa-dice-one'></i></a>");                                                           
                            }

                            // initialisation du plugin de zoom                                                 
                            const element = document.getElementById('img-block')
                            const resetButton = document.getElementsByClassName('img-zoom-reset');
                            const panzoom = Panzoom(element, 
                            {
                                // options here                                
                            });
                            // enable mouse wheel
                            const parent = element.parentElement
                            parent.addEventListener('wheel', panzoom.zoomWithWheel);                        
                            resetButton[0].addEventListener('click', (e) => e.preventDefault());
                            resetButton[0].addEventListener('click', panzoom.reset);
                            resetButton[1].addEventListener('click', (e) => e.preventDefault());
                            resetButton[1].addEventListener('click', panzoom.reset);
                        }           
                        else
                        {
                            $("#img-block").html("");
                            $(".block-img-change").html("");
                        }

                        startSwitchImage();
                    }
                    else
                    {
                        console.log('message error : ' + result);
                        console.log(result);
                    } 
                    
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
                 $("#text-list-lot").val("");
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

    btnControle.on('click',function(e)
    {
        var nbLot = countNbLot(textListLot.val());        
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
                list_champs:selectBoxListChamps.val()
            }              

            // Traitement Image Vide 
            $.post(HostLink+'/proccess/ajax/actioniec/controle_unitaire.php',   // url
                { myData: JSON.stringify(data1) }, // data to be submit
                    function(data, status, jqXHR) 
                    {
                        var result = JSON.parse(data);                               
                        
                        if(result[0] == "success")
                        {
                            if(result[1].length != 0)
                            {
                                
                                // success callback
                                // display result    
                                $("#liste-indic li:eq(0)").html("Récupération des champs : (" + result[1].length + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                                $("#liste-indic li:eq(0)").fadeIn(1000);

                                // Création dynamic du tableau des données
                                $("#table_container").html('<div class="table-responsive">'+
                                '<table class="table table-bordered" id="dataTableListeActes" width="100%" cellspacing="0">'+
                                '<thead>'+
                                    '<tr id="thead-th-modif">'+                               																																							
                                    '</tr>'+
                                '</thead>'+
                                '<tbody id="TableListeActes">'+
                                '</tbody>'+
                                '<tfoot>'+ 
                                '<tr id="tfoot-th-modif"></tr>'+                        
                                '</tfoot>'+
                                '</table>'+
                                '</div>');

                                // Ajout dynamic des champs modifiables
                                htmlFormField = ""
                                data1.list_champs.forEach((el,i) => {
                                    
                                    if((i+1)%2 != 0)
                                    {
                                        htmlFormField +="<hr/>"
                                        htmlFormField +='<div class="row"><div class="form-group col-md-6">'
                                                        +'<label for="field-'+ el +'"> ' + el + ' </label>'
                                                        +'<input type="text" class="form-control form-fillables" id="field-'+ el +'" aria-describedby="field-'+ el +'" placeholder=""/></div>'
                                        htmlFormField += (data1.list_champs.length == (i+1)) ?  "</div>" : ""                                                                                  
                                    }
                                    else
                                    {
                                        htmlFormField +='<div class="form-group col-md-6"><label for="field-'+ el +'"> '+ el +' </label>'
                                                    + '<input type="text" class="form-control form-fillables" id="field-'+ el +'" aria-describedby="field-'+ el +'" placeholder="" /></div></div>'
                                    }
                                })
                                $("#form-fields-fillables").html(htmlFormField)

                                // Redéfinition du click sur le bouton effacer                            
                                $(".btn-form-modal-cancel").on("click", function(e) 
                                {                
                                    // Rétablissement des champs du formulaire    
                                    $("#form-fields-fillables .form-control").each((i,e) => {  e.value = ""})
                                    $("#img-block").html("");                                                                                                                    
                                });

                                // Redéfinition du clickk sur le boutton de modification
                                $('#dataTableListeActes').on('draw.dt', function () {
                                    startEditActe();                           
                                });

                                // paramétrage de l'affichage
                                notifResultat.fadeIn("slow");
                                ResultatData.fadeIn("slow");
                                $("#notif-Resultat-1").text(result[1].length);                            

                                HtmlTableHead = (result[1].length > 0 ) ? Object.hasOwnProperty.call(result[1][0], "id_acte") ?  "<th> Modif </th>" : "" : "";
                                for (const key in result[1][0]) 
                                {
                                    if (Object.hasOwnProperty.call(result[1][0], key)) 
                                    {
                                        HtmlTableHead += '<th> ' + key + '</th>';                                                                                            
                                    }
                                }
                                $("#thead-th-modif").html(HtmlTableHead);                                                                                                                                                                                     
                                $("#tfoot-th-modif" ).html(HtmlTableHead); 
                                                        
                                // injection des données                             
                                htmlDataTable = "";    
                                result[1].forEach(e => { 
                                                                    
                                    htmlDataTable += "<tr id='ActeRow"+ e.id_acte +"'>";
                                    htmlDataTable += (result[1].length > 0) ? Object.hasOwnProperty.call(result[1][0], "id_acte") ? '<td class="text-center"> <a href="#" class="btn-edit" idActe="'+ e.id_acte +'" id_lot="'+ e.id_lot +'" imagepath="'+ e.imagepath +'" style="color:gray;" data-toggle="modal" data-target="#ActeModal"><i class="fas fa-highlighter"></i></a></td>' : "" : "";
                                    
                                    for (const key in result[1][0]) 
                                    {
                                        if (Object.hasOwnProperty.call(e, key)) 
                                        {
                                            htmlDataTable += '<td name="'+key+'"> '+ e[key] +'</td>';
                                        }
                                    }                             
                                    htmlDataTable += '</tr>';

                                    // ajout dans le tableau des Actes
                                    // listeActes.push({id_acte:e.id_acte,num_acte:e.num_acte});
                                });                                

                                $("#dataTableListeActes").dataTable().fnDestroy();                                             
                                $("#TableListeActes").html(htmlDataTable);
                                initDataTable($('#dataTableListeActes')); 
                            }
                            else
                            {
                                $("#liste-indic li:eq(0)").html("Récupération des champs : (" + result[1].length + ") <i class='fas fa-check text-success' style='margin-left:5px;font-size:20px;'></i>");
                                $("#liste-indic li:eq(0)").fadeIn(1000);
                                $("#table_container").html("")
                            }                            
                            
                            // Dissimulation du loader       
                            formLoader.css("display","none");                            
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