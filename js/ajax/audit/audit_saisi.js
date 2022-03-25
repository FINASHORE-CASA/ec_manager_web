$(document).ready(function() 
{    
    var HostLink = window.location.href.split("/")[0] +"//"+ window.location.href.split("/")[2]+ "/" +window.location.href.split("/")[3]
    HostLink = HostLink.includes(".php") ? "." : HostLink

    var audit_infos = [{
        selector:"AuditSaisi",
        module:"audit_saisi",
        status_lot:"V",
        list_lots:[]
    },{
        selector:"AuditControle1",
        module:"audit_controle_1",
        status_lot:"C", // C
        list_lots:[]
    },{
        selector:"AuditControle2",
        module:"audit_controle_2",
        status_lot:"T", // T
        list_lots:[]
    }]    

    function download(data_excel,fileName) 
    {        
       if(data_excel.length > 0)
       {
           $.post(HostLink+'/proccess/ajax/xlsx/generate.php',
           { myData: JSON.stringify(data_excel) },
           function(data, status, jqXHR)
           {              
               let result = JSON.parse(data)

               if(result[0] == "success")
               {                    
                    $("#download").attr("href",result[1])
                    $("#download").attr("download",fileName)
                    $("#download")[0].click()
               }
           })
       } 
    }  

    $("#audit_controle1_dl").on("click",function(e)
    {         
        var today = new Date()
        var dateNow = `${today.getDate()}_${(today.getMonth()+1)}_${today.getFullYear()}`

       download(audit_infos[1].list_lots,`Lot_disponible_audit_controle1_${dateNow}.xlsx`)        
       e.preventDefault() 
    })   
    // ----------------------------------    
    
    $("#audit_controle2_list_lot").on("click",function(e)
    {         
       $("#list-all-lot-dispo").val("")
       $("#list-all-lot-dispo-nb").html(audit_infos[2].list_lots.length) 
       audit_infos[2].list_lots.forEach((v) => 
       {           
            $("#list-all-lot-dispo").val($("#list-all-lot-dispo").val() + `${ v.id_lot} \n`)
       });
       e.preventDefault() 
    })
    // ------------------------------

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
        })
    }

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
    // ----------------------------------------------------------------

    function lunchAudit()
    {
        // Click sur Bonton Auditer 
        $("#btn-lunch-audit").on("click", function (e)
        {
            e.preventDefault()
            if($(`input[name="radioLotSelected"]:checked`).length != 0)
            {
                // Lancement des loader
                $("#table_container").html(`<div class="text-center m-5"><i class="fa fa-spinner fa-spin" style="font-size:4rem"></i></div>`)                
                $("#card-list-lot-audit").css("display", "none")                
                $("#card-loader-lot-audit").css("display", "inherit")                

                let id_lot = $(`input[name="radioLotSelected"]:checked`).attr("id")  
                id_lot = id_lot.replace("radioLotSelected","")
                
                let data1 = {id_lot:id_lot,
                             id_user_audit:$("#field-Id_user").val().trim(),
                             type_audit:0,
                             percent_ech_audit:$("#ech_value").val().trim(),
                             list_champs:$("#list_champs").val()
                             }

                $.post(`${HostLink}/proccess/ajax/audit/checkAuditLot.php`,
                        {myData: JSON.stringify(data1)},
                        (data) => 
                        {   
                            try
                            {   
                                let result = JSON.parse(data)                         
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
                                        htmlFormField +=`<div class="row">
                                                <div class="col-md-6">
                                                    <div class="col-md-12">
                                                        <label for="field-${el}"> ${el} </label>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control form-fillables" id="field-${el}" aria-describedby="field-${el}" readonly="true"/>
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">
                                                                    <input id="controle_field-${el}" type="checkbox" aria-label=""/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        `
                                        htmlFormField += (data1.list_champs.length == (i+1)) ?  "</div>" : ""                                                                                  
                                    }
                                    else
                                    {
                                        htmlFormField += `<div class="col-md-6">
                                                            <div class="input-group">
                                                                <div class="col-md-12">
                                                                    <label for="field-${el}"> ${el} </label>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control form-fillables" id="field-${el}" aria-describedby="field-${el}"  readonly="true"/>
                                                                        <div class="input-group-append">
                                                                            <div class="input-group-text">
                                                                                <input id="controle_field-${el}" type="checkbox" aria-label="" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>`
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

                                HtmlTableHead = (result[2].length > 0 ) ? Object.hasOwnProperty.call(result[2][0], "id_acte") ?  "<th> Modif </th>" : "" : "";
                                for (const key in result[2][0]) 
                                {
                                    if (Object.hasOwnProperty.call(result[2][0], key)) 
                                    {
                                        HtmlTableHead += '<th> ' + key + '</th>';                                                                                            
                                    }
                                }
                                $("#thead-th-modif").html(HtmlTableHead);                                                                                                                                                                                     
                                $("#tfoot-th-modif" ).html(HtmlTableHead); 
                                                        
                                // injection des données                             
                                htmlDataTable = "";  
                                ListeActes = [];  
                                result[2].forEach(e => { 
                                                                    
                                    htmlDataTable += "<tr id='ActeRow"+ e.id_acte +"'>";
                                    htmlDataTable += (result[2].length > 0) ? Object.hasOwnProperty.call(result[2][0], "id_acte") ? '<td class="text-center"> <a href="#" class="btn-edit" idActe="'+ e.id_acte +'" id_lot="'+ e.id_lot +'" imagepath="'+ e.imagepath +'" style="color:gray;" data-toggle="modal" data-target="#ActeModal"><i class="fas fa-eye"></i></a></td>' : "" : "";
                                    
                                    for (const key in result[2][0]) 
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
                                // ---------------------------------------------------------------- 


                                // Chargement des informations du lot 
                                $("#card-loader-lot-audit").css("display", "none")                
                                $("#card-details-lot-auditer").fadeIn();

                            }
                            catch (err)
                            {
                                alert(err);
                            }                        
                        })
            }
        });
    }

    // Récupération des lot à Disponible à Auditer 
    function getLotAuditAgent()
    {   
        let htmlLoader = '<tr><td colspan="7" class="text-center" style="font-size:1rem"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i></td></tr>'
        $("#TableAgentLotAgentDispo").html(htmlLoader)
        var data1 = {
                    id_user:$("#field-Id_user").val(),
                    type_audit:"auditsaisi",
                    status_lot:"V"
                }

        $.post(`${HostLink}/proccess/ajax/audit/getLotAuditAgent.php`,
            {myData: JSON.stringify(data1)},(data) => 
            {
                try {
                    let res = JSON.parse(data)

                    let htmlData = ""
                    res[1].forEach((v) => {
                        htmlData += `
                            <tr class="card mb-1" style="border-top:2px gray solid;">
                            <td>
                              <label for="radioLotSelected${v.id_lot}" style="width:70%">
                                ${v.id_lot}
                              </label   >
                              <input id="radioLotSelected${v.id_lot}" type="radio" name="radioLotSelected" class="form-control float-right" style="color:#4e73df;font-size:3px;width:30%;" />
                            </td>
                          </tr>`
                    });
                    $("#nb-liste-lot-agent").html(res[1].length)
                    $("#TableAgentLotAgentDispo").html(htmlData)

                    lunchAudit();                                     
                }
                catch (err) 
                {
                    $("#TableAgentLotAgentDispo").html("")
                    alert(err)
                }
            })
    }   
    // ---------------------------------------------    

    $("#btn-retour-list-audit").on("click", function(e) {

        // Lancement des loader
        $("#table_container").html(`<div class="text-center m-5"><i class="fa fa-spinner fa-spin" style="font-size:4rem"></i></div>`)                
        $("#card-loader-lot-audit").css("display", "inherit")         
        $("#card-details-lot-auditer").css("display", "none") 

        setTimeout(() => {
            
            $("#card-loader-lot-audit").css("display", "none")         
            $("#card-list-lot-audit").css("display", "inherit")                
            $("#table_container").html('')
        },1000)


        e.preventDefault()
    })

    // initialisation des déclencheurs
    getLotAuditAgent()
})