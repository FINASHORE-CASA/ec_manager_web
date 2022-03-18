$(document).ready(function() {

    var HostLink = window.location.href.split("/")[0] +"//"+ window.location.href.split("/")[2]+ "/" +window.location.href.split("/")[3];
    HostLink = HostLink.includes(".php") ? "." : HostLink;

    // Selection des indicateurs 
    var btnSearchActe = $("#btn-search_acte")
        ,formLoader = $("#form-lot-loader")
        ,textListLot = $("#text-reqs")
        ,txtControleNotif = $("#txt-nb-lot-notif")
        ,notifResultat = $("#notif-Resultat-bell")
        ,ResultatData = $("#resultat_data")
        ,alertBox = $("#alert_box")
        ,btnCorrect = $(".form-correct-btn");    

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

    $(".btn-form-modal-cancel").on("click", function(e) 
    {
        // Rétablissement des champs du formulaire
        $("#ActeModal .form-control").each((i,el) => {
            el.value = "";
        });        
        $("#field-Id_lot").html("");
        $("#img-block").html("");         
    });
    
    $(".form-update-save").on("click",function(e){            
        // Récupération de l'Id du click
        var data1 = {}   

        $("#ActeModal .form-control").each((i,el) => 
        {
            data1[el.id.replace("field-","")] = el.value.trim();            
        }); 

        $.post(HostLink+'/proccess/ajax/saisi/update_acte_all_req.php',   // url
        { myData: JSON.stringify(data1) }, // data to be submit
            function(data, status, jqXHR) 
            {
                var result = JSON.parse(data);                               
                console.log(result);
                
                if(result[0] == "success")
                {                    
                    // success callback
                    if(typeof result[1] === 'number' && typeof result[2] === 'number' && typeof result[3] === 'number')
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

    btnCorrect.on("click",function(e)
    {
        var data1 = { id_acte: $("#field-id_acte").val().trim(),} 
        $.post(HostLink+'/proccess/ajax/saisi/update_acte_req_correct.php',   // url
        { myData: JSON.stringify(data1) }, // data to be submit
            function(data, status, jqXHR) 
            {
                var result = JSON.parse(data);                               
                console.log(result);
                
                if(result[0] == "success")
                {             
                    $("#ActeModal").modal("hide");                        
                    $(".btn-form-modal-cancel").trigger("click");
                    $("#ActeRow"+data1.id_acte).fadeOut("slow");   
                    // mis en place de l'alert 
                    show_alert("success","Acte marqué comme contrôlé !","",3);                    
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
                    $("#image2").css("display","none");

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
    var startEditActe = function() 
    {
        var btnEdit = $(".btn-edit");

        // Remplissage des informations de l'acte
        btnEdit.on("click",function(e)
        {
            console.log($(this).attr("idActe"));

            if($(this).attr("idActe") != "undefined" && $(this).attr("idActe") != "")
            {
                // Récupération de l'Id du click
                var data1 = {
                    id_acte: $(this).attr("idActe"),
                    imagepath: $("#ActeRow"+$(this).attr("idActe")+" td[name='imagepath']").text().trim(),
                    id_lot: $("#ActeRow"+$(this).attr("idActe")+" td:eq(1)").text().trim()
                }     

                // Récupération des tds    
                const listTd = $("#ActeRow"+data1.id_acte+" td");   
                $("#form-acte-loader").css("display","block");              

                $("#field-Id_lot").html("");            

                $.post(HostLink+'/proccess/ajax/saisi/recup_acte_all_req.php',   // url
                    { myData: JSON.stringify(data1) }, // data to be submit
                    function(data, status, jqXHR) 
                    {
                        console.log(data);
                        var result = JSON.parse(data);                               
                        
                        if(result[0] == "success")
                        {                        
                            console.log(result[1]);
                            $("#field-Id_lot").html(result[1].id_lot);            

                            // information acte
                            for (const key in result[1]) 
                            {
                                if(Object.hasOwnProperty.call(result[1], key)) 
                                {                                
                                    $("#field-"+key).val(result[1][key]);   
                                }
                            }

                            // deces
                            for (const key in result[4]) 
                            {
                                if (Object.hasOwnProperty.call(result[4], key)) 
                                {                                
                                    $("#field-"+key).val(result[4][key]);   
                                }
                            }

                            // jugement
                            for (const key in result[5]) 
                            {
                                if (Object.hasOwnProperty.call(result[5], key)) 
                                {                                
                                    $("#field-"+key).val(result[5][key]);
                                }
                            }

                            // success callback
                            // display result                                  
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
                ); 
            }
            else
            {
                $("#ActeModal").modal("hide");
                alert("La modification de l'acte est impossible, veuillez spécifier l'id_acte dans la requête ");
            }
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

    btnSearchActe.on('click',function(e)
    {        
        e.preventDefault();        
        if(textListLot.val().trim() == "")
        {
            alert(" Le champ de la requête est vide ");
        }
        else if( textListLot.val().trim().toLowerCase().includes("update") 
                || textListLot.val().trim().toLowerCase().includes("delete") 
                || textListLot.val().trim().toLowerCase().includes("insert") 
                || textListLot.val().trim().toLowerCase().includes("drop")
                || textListLot.val().trim().toLowerCase().includes("modify") 
                || textListLot.val().trim().toLowerCase().includes("alter")
                || textListLot.val().trim().toLowerCase().split(';').length > 2
                || textListLot.val().trim().substring(0,6).toLowerCase() != "select")
        {
            alert("assurez vous d'avoir entrer 1 requête de selection valide");
        }
        else
        {
            // hide previous result
            formLoader.css("display","inherit");
            notifResultat.fadeOut("fast"); 
            ResultatData.fadeOut("fast");   
            $("#table_container").html("");

            // traitement des lots             
            var data1 = {
                reqs: textListLot.val().trim(),
                show_all: $("#show_all")[0].checked ? 1 : 0
            }              

            // Traitement Image Vide 
            $.post(HostLink+'/proccess/ajax/saisi/exec_req_acte.php',   // url
                { myData: JSON.stringify(data1) }, // data to be submit
                    function(data, status, jqXHR) 
                    {
                        console.log(data);

                        var result = JSON.parse(data);                               
                        
                        if(result[0] == "success" && result[1].length > 0 )
                        {
                            $("#table_container").html(' <div class="table-responsive">'+
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

                            $('#dataTableListeActes').on('draw.dt', function () {
                                startEditActe();                           
                            });

                            // success callback
                            // paramétrage de l'affichage
                            notifResultat.fadeIn("slow");
                            ResultatData.fadeIn("slow");                            

                            HtmlTableHead = (result[1].length > 0 ) ? Object.hasOwnProperty.call(result[1][0], "id_acte") ?  "<th> Modif </th>" : "" : "";
                            for (const key in result[1][0]) 
                            {
                                if (Object.hasOwnProperty.call(result[1][0], key)) 
                                {
                                    HtmlTableHead += '<th> ' + key + '</th>';                                                                                            
                                }
                            }
                            console.log(HtmlTableHead);
                            $("#thead-th-modif").html(HtmlTableHead);                                                                                                                                                                                     
                            $("#tfoot-th-modif" ).html(HtmlTableHead); 
                                                    
                            // injection des données                             
                            htmlDataTable = "";    
                            result[1].forEach(e => { 
                                                                
                                htmlDataTable += "<tr id='ActeRow"+ e.id_acte +"'>";
                                htmlDataTable += (result[1].length > 0 ) ? Object.hasOwnProperty.call(result[1][0], "id_acte") ? '<td class="text-center"> <a href="#" class="btn-edit" idActe="'+ e.id_acte +'" style="color:gray;" data-toggle="modal" data-target="#ActeModal"><i class="fas fa-highlighter"></i></a></td>' : "" : "";
                                
                                for (const key in result[1][0]) 
                                {
                                    if (Object.hasOwnProperty.call(e, key)) 
                                    {
                                        htmlDataTable += '<td name="'+key+'"> '+ e[key] +'</td>';
                                    }
                                }                             
                                htmlDataTable += '</tr>';
                            });

                            $("#dataTableListeActes").dataTable().fnDestroy();                                             
                            $("#TableListeActes").html(htmlDataTable);
                            initDataTable($('#dataTableListeActes')); 
                            
                            // Dissimulation du loader       
                            formLoader.css("display","none");
                        }
                        else
                        {
                            // Dissimulation du loader       
                            formLoader.css("display","none");
                            console.log('message error : ' + result);
                            console.log(result);
                        }
                    }
                );             
        }
    });
});