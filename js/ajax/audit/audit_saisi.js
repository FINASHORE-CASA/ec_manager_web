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

    // Lancement de l'affichage des lots
    $("#audit_saisie_list_lot").on("click",function(e)
    {         
       $("#list-all-lot-dispo").val("")
       $("#list-all-lot-dispo-nb").html(audit_infos[0].list_lots.length) 
       audit_infos[0].list_lots.forEach((v) => 
       {           
            $("#list-all-lot-dispo").val($("#list-all-lot-dispo").val() + `${ v.id_lot} \n`)
       });
       e.preventDefault() 
    })

    $("#audit_controle1_list_lot").on("click",function(e)
    {         
       $("#list-all-lot-dispo").val("")
       $("#list-all-lot-dispo-nb").html(audit_infos[1].list_lots.length) 
       audit_infos[1].list_lots.forEach((v) => 
       {           
            $("#list-all-lot-dispo").val($("#list-all-lot-dispo").val() + `${ v.id_lot} \n`)
       });
       e.preventDefault() 
    })
    
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
                }
                catch (err) 
                {
                    $("#TableAgentLotAgentDispo").html("")
                    alert(err)
                }
            })
    }   
    // ---------------------------------------------    

    // Récupération des agents d'Audit
    function getAgentsAuditList(typeAuditSelector,typeAudit,status_lot)
    {        
        htmlDataTableLoader = '<tr><td colspan="7" class="text-center" style="font-size:1rem"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i></td></tr>'                    
        $(`#TableAgent${typeAuditSelector}`).html(htmlDataTableLoader)

        $.post(`${HostLink}/proccess/ajax/audit/getAgentAudit.php`,
        {myData: JSON.stringify({ typeAudit:typeAudit })},(data) => 
        {
            try {
                let res = JSON.parse(data);
                if(res[0] == "success")
                {
                    htmlDataTable = ""
                    res[1].forEach((e) => {
                         htmlDataTable += `<tr class="card mb-1" style="border-top:2px #4e73df solid;">
                              <td> ${e.login} <a type="button" id="RowAgent${typeAuditSelector}${e.id_user}" 
                                class="float-right agentLotAudit" style="color:#4e73df;"
                                id_user="${e.id_user}"
                                type_audit="${typeAuditSelector}"
                                login_agent="${e.login}"
                                status_lot="${status_lot}"
                                data-toggle="modal" data-target="#AgentAffectationAudit"> <i class="fa fa-cog" aria-hidden="true"></i> 
                              </a> </td>
                            </tr>`
                    })
                    $(`#TableAgent${typeAuditSelector}`).html(htmlDataTable)
                    getInfosAgentLotAudit()
                }
                else
                {
                    console.log(data)
                    $(`#TableAgent${typeAuditSelector}`).html("")
                }                
            } catch (error) {
                console.log(error)
                console.log(data)
            }
        })
    }    

    // Formating du resultat de info Agent
    function formatDataAgentAffect(data)
    {
        let htmlData = ""  
        data.forEach((e) => {
            htmlData += `<span class="badge badge-dark mr-2"> ${e.id_lot} | 
            <a href="#" id_supp_lot_aff="${e.id}" class="supp_lot_affect_agent"> <span class="fas fa-times-circle text-danger"> </span> </a> 
            </span>`                     
        })              
        $("#list-lots-user").html(htmlData)

        $(".supp_lot_affect_agent").on("click", function(e){

            let data1 = {
                id:$(this).attr("id_supp_lot_aff")            
            }

            $.post(`${HostLink}/proccess/ajax/audit/delLotAgentAudit.php`,
                {myData: JSON.stringify(data1)},(data) => { 
                    try 
                    {
                        res = JSON.parse(data)
                        if(res[0] == "success")
                        {
                            $(`.supp_lot_affect_agent[id_supp_lot_aff=${data1.id}]`).parent().fadeOut("fast")                                                    
                            $(`.supp_lot_affect_agent[id_supp_lot_aff=${data1.id}]`).removeClass("supp_lot_affect_agent")                                                    
                            $("#FormNbLotAgent").html($(".supp_lot_affect_agent").length)
                        }
                        else
                        {
                            alert(res[0] +'\n' + res[1] )
                        }
                    }
                    catch (err)
                    {
                        alert(err)
                    }
            })

            e.preventDefault();
        })
    }

    // Click sur le bouton d'affectation d'un agent
    function getInfosAgentLotAudit()
    {
        $(".agentLotAudit").on("click",function() 
        {
            $("#list-lot-a-aff").val("")                      
            $("#AgentAuditLogin").html(`<i class="fa fa-spinner fa-spin" style="font-size:1rem;" aria-hidden="true"></i>`);
            $("#formAffectAgentAudit").css("display", "none");
            $("#AgentAuditAffectLoader").css("display", "block");

            $("#btn-valider-affectation").attr("id_user",$(this).attr("id_user"))
            $("#btn-valider-affectation").attr("type_audit",$(this).attr("type_audit"))
            $("#btn-valider-affectation").attr("status_lot",$(this).attr("status_lot"))

            var data1 = {id_user:$(this).attr("id_user"),
                         type_audit:$(this).attr("type_audit"),
                         id_user_aff:$("#field-Id_user").val(), 
                         login_agen:$(this).attr("login_agent")                   
                        }

            $.post(`${HostLink}/proccess/ajax/audit/getAgentAuditLotAffecte.php`,
            {myData : JSON.stringify(data1)},(data) => {                
                try {
                    let res = JSON.parse(data)
                    // ajouter 
                    $("#AgentAuditLogin").html(` <i class="far fa-user-circle"></i> ${data1.login_agen} 
                                                 <span id="FormNbLotAgent" class="badge badge-light ml-2"> ${res[1].length} </span>`);
                    formatDataAgentAffect(res[1])
                    $("#AgentAuditAffectLoader").css("display", "none");
                    $("#formAffectAgentAudit").fadeIn();
                } 
                catch(err)
                {
                    $("#AgentAuditAffectLoader").css("display", "none");
                    console.log(err)
                }
            })
        })        
    }

    // Valider affectation de lots
    $("#btn-valider-affectation").on("click", function () 
    {
        if($("#list-lot-a-aff").val().trim().length > 0)
        {            
            $("#btn-valider-affectation").html(`valider <i class="fa fa-spinner fa-spin" style="font-size:1rem;" aria-hidden="true"></i>`)
            var data1 = {id_lot:$("#list-lot-a-aff").val().trim().replace(/[\n\r]/g,','),
                        type_audit:$(this).attr("type_audit"),
                        id_audit_user:$(this).attr("id_user"),
                        id_user_aff:$("#field-Id_user").val(),
                        status_lot:$(this).attr("status_lot")}                      

            $.post(`${HostLink}/proccess/ajax/audit/postAgentAuditLotAffecte.php`,
                {myData : JSON.stringify(data1)},(data) => {
                    try {
                        let res = JSON.parse(data)

                        // ajouter 
                        $("#FormNbLotAgent").html(res[1].length)
                        formatDataAgentAffect(res[1])
                        $("#btn-valider-affectation").html(`valider <i class="fa fa-check-circle" aria-hidden="true"></i>`)
                    } 
                    catch(err)
                    {
                        $("#btn-valider-affectation").html(`valider <i class="fa fa-check-circle" aria-hidden="true"></i>`)
                        alert(err)
                    }
                })                                                                                           
        }
    });

    // initialisation des déclencheurs
    getLotAuditAgent()
})