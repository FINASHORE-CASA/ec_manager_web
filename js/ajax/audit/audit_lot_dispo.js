$(document).ready(function() 
{    
    var HostLink = window.location.href.split("/")[0] +"//"+ window.location.href.split("/")[2]+ "/" +window.location.href.split("/")[3]
    HostLink = HostLink.includes(".php") ? "." : HostLink

    var liste_lot_audit_saisie = [],liste_lot_audit_controle1 = [],liste_lot_audit_controle2 = []

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
                    console.log(result[1])
                    $("#download").attr("href",result[1])
                    $("#download").attr("download",fileName)
                    $("#download")[0].click()
               }
           })
       } 
    }  

    $("#audit_saisie_dl").on("click",function(e)
    {         
        var today = new Date()
        var dateNow = `${today.getDate()}_${(today.getMonth()+1)}_${today.getFullYear()}`

       download(liste_lot_audit_saisie,`Lot_disponible_audit_saisie_${dateNow}.xlsx`)        
       e.preventDefault() 
    })   

    $("#audit_controle1_dl").on("click",function(e)
    {         
        var today = new Date()
        var dateNow = `${today.getDate()}_${(today.getMonth()+1)}_${today.getFullYear()}`

       download(liste_lot_audit_controle1,`Lot_disponible_audit_controle1_${dateNow}.xlsx`)        
       e.preventDefault() 
    })   

    $("#audit_controle2_dl").on("click",function(e)
    {         
        var today = new Date()
        var dateNow = `${today.getDate()}_${(today.getMonth()+1)}_${today.getFullYear()}`

       download(liste_lot_audit_controle2,`Lot_disponible_audit_controle2_${dateNow}.xlsx`)        
       e.preventDefault() 
    })  

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
    async function getLotAuditLot(status,typeAuditSelector)
    {                  
        htmlDataTableLoader = '<tr><td colspan="7" class="text-center" style="font-size:2rem"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i></td></tr>'                    
        $(`#Table${typeAuditSelector}`).html(htmlDataTableLoader)

        // Récupération des lots Audit Saisi 
        return await $.post(`${HostLink}/proccess/ajax/audit/auditLotDispo.php`, {myData : JSON.stringify({status: status})}, 
                function(data) 
                {             
                    try {
                        var result = JSON.parse(data)                               
                        
                        if(result[0] == "success")
                        {                          
                            // injection des données                             
                            htmlDataTable = ""    
                            result[1].forEach(e => {   
                                
                                htmlDataTable += `<tr id='Row${typeAuditSelector}${e.id_lot}'>`
                                                    +"<td class='text-center'>" + e.id_lot +"</td>"
                                                    +"<td class='text-center'>" + e.id_commune +"</td>"
                                                    +"<td class='text-center'>" + e.id_bureau +"</td>"
                                                    +"<td class='text-center'>" + e.nb_actes +"</td>"
                                                    +"<td class='text-center'>" + e.date_saisie + "</td>"                                        
                                                + "</tr>"                        
                            })
                            
                            $(`#Table${typeAuditSelector}`).html("")
                            $(`#dataTable${typeAuditSelector}`).dataTable().fnDestroy()
                            $(`#Table${typeAuditSelector}`).html(htmlDataTable)
                            initDataTable($(`#dataTable${typeAuditSelector}`))                                                                                                                           
                        }
                        else
                        {
                            console.log('message error : ' + result)                                                                                                                    
                        }
                    }
                    catch(err)
                    {
                        console.log(err)
                    }
                }
            )     
    }  
    
    (async () => {
       d = await getLotAuditLot('V','AuditSaisi')                 
       liste_lot_audit_saisie = JSON.parse(d)[1]       
    })()

    $('a[href="#AuditSaisi"').on("click",async () => {
       d = await getLotAuditLot('V','AuditSaisi')                 
       liste_lot_audit_saisie = JSON.parse(d)[1] 
    }) 

    $('a[href="#AuditControle1"').on("click",async () => {
       d = await getLotAuditLot('C','AuditControle1')  //C               
       liste_lot_audit_controle1 = JSON.parse(d)[1]     
    }) 
    
    $('a[href="#AuditControle2"').on("click",async () => {
       d = await getLotAuditLot('T','AuditControle2') //T                
       liste_lot_audit_controle2 = JSON.parse(d)[1]       
    }) 
    // ---------------------------------------------    

    // Récupération des agents d'Audit
    function getAgentsAuditList(typeAuditSelector,typeAudit)
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
                    console.log(res)
                    htmlDataTable = ""
                    res[1].forEach((e) => {
                         htmlDataTable += `<tr class="card mb-1" style="border-top:2px #4e73df solid;">
                              <td> ${e.login} <a type="button" id="RowAgent${typeAuditSelector}${e.id_user}" 
                                class="float-right agentLotAudit" style="color:#4e73df;"
                                id_user="${e.id_user}"
                                type_audit="${typeAuditSelector}"
                                login_agent="${e.login}"
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
        });
    }
    getAgentsAuditList("AuditSaisi","audit_saisi")

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
            $("#btn-valider-affectation").attr("status_lot","V") // V

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
    })
})