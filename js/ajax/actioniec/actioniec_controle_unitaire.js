$(document).ready(function() 
{    
    var HostLink = window.location.href.split("/")[0] +"//"+ window.location.href.split("/")[2]+ "/" +window.location.href.split("/")[3];
    HostLink = HostLink.includes(".php") ? "." : HostLink;

    // Liste champs spéciaux
    var listeChampsMoisG = ["md_naissance_g","md_naissance_pere_g","md_naissance_mere_g","md_etabli_acte_g"]
    var listeChampsMoisH = ["md_naissance_h","md_naissance_pere_h","md_naissance_mere_h","md_etabli_acte_h"]
    var ListeMoisG = ["Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Aôut","Septembre","Octobre","Novembre","Décembre"]
    var ListeMoisH = ["JanvierH","FévrierH","MarsH","AvrilH","MaiH","JuinH","JuilletH","AôutH","SeptembreH","OctobreH","NovembreH","DécembreH"]
    var ListeIdField = ["id_nationlite","id_nationalite_pere","id_profession_pere","id_nationalite_mere","id_profession_mere","id_officier","id_ville_naissance","id_ville_naissance_mere","id_ville_naissance_pere","id_ville_residence_parents","id_profession","id_ville_deces","id_ville_adresse_mere","id_ville_adresse_pere","id_ville_adresse"]
    var ExtraIdData = {};

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
    var ListeActes = [];

    // Préparation des données à envoyer
    function countNbLot (txt) 
    {
        var txtArray =  txt.split("\n").filter(function(el) {return el.trim().length != 0});        
        return txtArray.length;
    };     

    function show_alert (theme_color,title,text="",time=5)
    {
        $("#alert-container").html('<div id="alert_box" class="alert alert-'+theme_color+' alert-dismissible fade show mt-2" role="alert">'
                                    +'<strong> '+title+' </strong>' + text
                                    +'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
                                    +'<span aria-hidden="true">&times;</span>'
                                   +'</button>'
                                +'</div>');

        setTimeout(() => { $("#alert-container #alert_box").fadeOut("slow");},(time*1000));                                
    }

    // Récupération des informations concernant les ids extra actes
    (() => {
        $.get(HostLink+'/proccess/ajax/actioniec/get_id_field_elements.php',   // url        
            function(data, status, jqXHR) 
            {   
                let res = JSON.parse(data);

                if(res[0] == "success")                          
                {
                    ExtraIdData["nationalites"] = res[1] 
                    ExtraIdData["professions"] = res[2] 
                    ExtraIdData["officiers"] = res[3] 
                    ExtraIdData["villes"] = res[4] 

                    console.log(ExtraIdData)
                }
                else
                {
                    alert("erreur recupération des elements du field id")
                    console.log(data)
                }
            }
        )
    })();

    function save_action(id_lot,id_acte,id_user_ctr)
    {
        var data1 = {id_lot:id_lot,id_acte:id_acte,id_user_ctr:id_user_ctr,type_action:"controle_unitaire"}

        $.post(HostLink+'/proccess/ajax/gestion_user/save_action.php',   // url
        { myData: JSON.stringify(data1) }, // data to be submit
            function(data, status, jqXHR) 
            {
                console.log("action enregistrer")
            }
        )
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
                    if(typeof result[1] === 'number' || typeof result[2] === 'number')
                    {
                        result[5].forEach(nomchamp => 
                        {
                            if(Object.keys(data1).some(e1=> {return e1 == nomchamp['cname']}))
                            {
                            let elValue =  Object.keys(data1).filter(e1=> {return e1 == nomchamp['cname']})[0];                                       
                            $("#ActeRow"+data1.id_acte+ " td[name='"+[elValue]+"']").html(data1[elValue]);   
                            }
                        });    

                        save_action(data1.id_lot,data1.id_acte,$("#field-Id_user").val());                         

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
            // $("#img-next").attr("disabled","true");
            // $("#img-prev").attr("disabled","true");
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
                        // Preparation des btn next et prev
                        // console.log(ListeActes);
                        // $("#img-current").html(`<i style="font-size:14px;text-decoration:underline;">#${ListeActes.filter(a=>a.id_acte == data1.id_acte)[0].num_acte}</i>`);
                        // let acte_next = ListeActes.filter(a=>a.id_acte > data1.id_acte).length > 0 ? ListeActes.filter(a=>a.id_acte > data1.id_acte)[0] : 0;
                        // let acte_prev = ListeActes.filter(a=>a.id_acte < data1.id_acte).length > 0 ? ListeActes.filter(a=>a.id_acte < data1.id_acte)[ListeActes.filter(a=>a.id_acte < data1.id_acte).length -1] : 0;

                        // if(acte_next)
                        // {
                        //     $("#img-next").attr("idActe",acte_next.id_acte);
                        //     $("#img-next").attr("id_lot",acte_next.id_lot);
                        //     $("#img-next").attr("imagepath",acte_next.imagepath);
                        //     $("#img-next").html(`<i class="fa fa-chevron-right"></i> <i style="font-size:10px;">${acte_next.num_acte}</i>`);
                        //     $("#img-next").removeAttr("disabled");
                        // }
                        // else
                        // {
                        //     $("#img-next").attr("id_acte","0");
                        //     $("#img-next").attr("id_lot","");
                        //     $("#img-next").attr("imagepath","");
                        //     $("#img-next").html(`<i class="fa fa-chevron-right"></i> <i style="font-size:10px;"> no </i>`);
                        //     $("#img-next").attr("disabled","true");
                        // }

                        // if(acte_prev)
                        // {
                        //     $("#img-prev").attr("idActe",acte_prev.id_acte);
                        //     $("#img-prev").attr("id_lot",acte_prev.id_lot);
                        //     $("#img-prev").attr("imagepath",acte_prev.imagepath);
                        //     $("#img-prev").attr("id_acte",acte_prev.id_acte);
                        //     $("#img-prev").html(`<i class="fa fa-chevron-left"></i> <i style="font-size:10px;">${acte_prev.num_acte}</i>`);
                        //     $("#img-prev").removeAttr("disabled");
                        // }
                        // else
                        // {
                        //     $("#img-prev").attr("id_acte","0");
                        //     $("#img-prev").attr("id_lot","");
                        //     $("#img-prev").attr("imagepath","");
                        //     $("#img-prev").attr("id_acte",acte_prev.id_acte);
                        //     $("#img-prev").html(`<i class="fa fa-chevron-left"></i> <i style="font-size:10px;"> no </i>`);
                        //     $("#img-prev").attr("disabled","true");                            
                        // }
                        // // -------------------------------------------------

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
        console.log(ListeActes);
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
                                        console.log(ExtraIdData.nationalites)
                                        if(listeChampsMoisG.includes(el) || listeChampsMoisH.includes(el))
                                        {
                                            htmlFormField +="<hr/>"
                                            htmlFormField +='<div class="row"><div class="form-group col-md-6">'
                                                            +'<label for="field-'+ el +'"> ' + el + ' </label>'
                                                            +'<select class="form-control form-fillables" id="field-'+ el +'">'  
                                                            +'<option value=""> Aucun </option>'
                                            if(el.trim().toLowerCase()[el.trim().length -1] === "g")
                                            {
                                                ListeMoisG.forEach((m,i)=> {                                                
                                                    htmlFormField += `<option value="${(i +1) < 10 ? "0" :""}${i + 1}" > ${m} </option>`
                                                })                                                                                                                                                        
                                            }                                                                                                             
                                            else
                                            {
                                                ListeMoisH.forEach((m,i)=> {                                                
                                                    htmlFormField += `<option value="${(i +1) < 10 ? "0" :""}${i + 1}" > ${m} </option>`
                                                })                                                                                                                                                        
                                            }         
                                            htmlFormField +='</select></div>'                                                 
                                            htmlFormField += (data1.list_champs.length == (i+1)) ?  "</div>" : ""                                                                                      
                                        }
                                        else if(ListeIdField.includes(el))
                                        {
                                            htmlFormField +="<hr/>"
                                            htmlFormField +='<div class="row"><div class="form-group col-md-6">'
                                                            +'<label for="field-'+ el +'"> ' + el + ' </label>'
                                                            +'<select class="form-control form-fillables" id="field-'+ el +'">'  
                                                            +'<option value=""> Aucun </option>'
                                        
                                            if(el.trim().toLowerCase().includes("nation"))
                                            {
                                                ExtraIdData.nationalites.forEach((m,i)=> {                                                
                                                    htmlFormField += `<option value="${m.id_nationalite}" > ${m.nationalite} </option>`
                                                })                                                                                                                                                        
                                            }                                                                                                             
                                            else if(el.trim().toLowerCase().includes("profession"))
                                            {
                                                ExtraIdData.professions.forEach((m,i)=> {                                                
                                                    htmlFormField += `<option value="${m.id_profession}" > ${m.profession} </option>`
                                                })                                                                                                                                                        
                                            }        
                                            else if(el.trim().toLowerCase().includes("officier"))
                                            {
                                                ExtraIdData.officiers.forEach((m,i)=> {                                                
                                                    htmlFormField += `<option value="${m.id_officier}" > ${m.nom_officier_ar} </option>`
                                                })  
                                            }
                                            else if(el.trim().toLowerCase().includes("ville"))
                                            {
                                                ExtraIdData.villes.forEach((m,i)=> {                                                
                                                    htmlFormField += `<option value="${m.id_ville}" > ${m.lib_ville} </option>`
                                                }) 
                                            }

                                            htmlFormField +='</select></div>'                                                 
                                            htmlFormField += (data1.list_champs.length == (i+1)) ?  "</div>" : ""                                                                                      
                                        }
                                        else
                                        {
                                            htmlFormField +="<hr/>"
                                            htmlFormField +='<div class="row"><div class="form-group col-md-6">'
                                                            +'<label for="field-'+ el +'"> ' + el + ' </label>'
                                                            +'<input type="text" class="form-control form-fillables" id="field-'+ el +'" aria-describedby="field-'+ el +'" placeholder=""/></div>'
                                            htmlFormField += (data1.list_champs.length == (i+1)) ?  "</div>" : ""                                                                                  
                                        }
                                    }
                                    else
                                    {
                                        if(listeChampsMoisG.includes(el) || listeChampsMoisH.includes(el))
                                        {
                                            htmlFormField +='<div class="form-group col-md-6"><label for="field-'+ el +'"> '+ el +' </label>'
                                                          +'<select class="form-control form-fillables" id="field-'+ el +'">'
                                                          +'<option value=""> Aucun </option>'                                                            
                                            if(el.trim().toLowerCase()[el.trim().length -1] === "g")
                                            {
                                                ListeMoisG.forEach((m,i)=> {                                                
                                                    htmlFormField += `<option value="${(i +1) < 10 ? "0" :""}${i + 1}" > ${m} </option>`
                                                })                                                                                                                                                        
                                            }                                                                                                             
                                            else
                                            {
                                                ListeMoisH.forEach((m,i)=> {                                                
                                                    htmlFormField += `<option value="${(i +1) < 10 ? "0" :""}${i + 1}" > ${m} </option>`
                                                })                                                                                                                                                        
                                            }                                                                                                         
                                            htmlFormField += '</select></div></div>'                                   
                                        }
                                        else if(ListeIdField.includes(el))
                                        {
                                            htmlFormField +='<div class="form-group col-md-6"><label for="field-'+ el +'"> '+ el +' </label>'
                                                          +'<select class="form-control form-fillables" id="field-'+ el +'">'
                                                          +'<option value=""> Aucun </option>'  
                                                                                    
                                            if(el.trim().toLowerCase().includes("nation"))
                                            {
                                                ExtraIdData.nationalites.forEach((m,i)=> {                                                
                                                    htmlFormField += `<option value="${m.id_nationalite}" > ${m.nationalite} </option>`
                                                })                                                                                                                                                        
                                            }                                                                                                             
                                            else if(el.trim().toLowerCase().includes("profession"))
                                            {
                                                ExtraIdData.professions.forEach((m,i)=> {                                                
                                                    htmlFormField += `<option value="${m.id_profession}" > ${m.profession} </option>`
                                                })                                                                                                                                                        
                                            }        
                                            else if(el.trim().toLowerCase().includes("officier"))
                                            {
                                                ExtraIdData.officiers.forEach((m,i)=> {                                                
                                                    htmlFormField += `<option value="${m.id_officier}" > ${m.nom_officier_ar} </option>`
                                                })  
                                            }
                                            else if(el.trim().toLowerCase().includes("ville"))
                                            {
                                                ExtraIdData.villes.forEach((m,i)=> {                                                
                                                    htmlFormField += `<option value="${m.id_ville}" > ${m.lib_ville} </option>`
                                                }) 
                                            }

                                            htmlFormField += '</select></div></div>'                                   
                                        }
                                        else
                                        {
                                            htmlFormField +='<div class="form-group col-md-6"><label for="field-'+ el +'"> '+ el +' </label>'
                                                        + '<input type="text" class="form-control form-fillables" id="field-'+ el +'" aria-describedby="field-'+ el +'" placeholder="" /></div></div>'
                                        }
                                    }
                                })
                                $("#form-fields-fillables").html(htmlFormField)

                                // Redéfinition du click sur le bouton effacer                            
                                $(".btn-form-modal-cancel").on("click", function(e) 
                                {                
                                    console.log($("#field-id_lot").val())
                                    console.log($("#field-id_acte").val())
                                    console.log($("#field-Id_user").val())
                                    save_action($("#field-id_lot").val(),$("#field-id_acte").val(),$("#field-Id_user").val());                         
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
                                ListeActes = [];  
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
                                    ListeActes.push({id_acte:e.id_acte,num_acte:e.num_acte,id_lot:e.id_lot,imagepath:e.imagepath});                                    
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