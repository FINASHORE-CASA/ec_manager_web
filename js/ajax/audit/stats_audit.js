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

    // Lancement du téléchargement des stats lot
    $("#audit_saisie_dl").on("click",function(e)
    {         
        var today = new Date()
        var dateNow = `${today.getDate()}_${(today.getMonth()+1)}_${today.getFullYear()}`

       download(audit_infos[0].list_lots,`Stats_audit_saisie_${dateNow}.xlsx`)        
       e.preventDefault() 
    })   

    $("#audit_controle1_dl").on("click",function(e)
    {         
        var today = new Date()
        var dateNow = `${today.getDate()}_${(today.getMonth()+1)}_${today.getFullYear()}`

       download(audit_infos[1].list_lots,`Stats_audit_controle1_${dateNow}.xlsx`)        
       e.preventDefault() 
    })   

    $("#audit_controle2_dl").on("click",function(e)
    {         
        var today = new Date()
        var dateNow = `${today.getDate()}_${(today.getMonth()+1)}_${today.getFullYear()}`

       download(audit_infos[2].list_lots,`Stats_audit_controle2_${dateNow}.xlsx`)        
       e.preventDefault() 
    })  
    // ----------------------------------

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
    async function getLotAuditLot(typeAuditSelector)
    {                  
        htmlDataTableLoader = '<tr><td colspan="12" class="text-center" style="font-size:2rem"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i></td></tr>'                    
        $(`#Table${typeAuditSelector}`).html(htmlDataTableLoader)

        // Récupération des lots Audit Saisi 
        return await $.post(`${HostLink}/proccess/ajax/audit/getStatsAuditLot.php`, {myData : JSON.stringify({status: typeAuditSelector})}, 
                function(data) 
                {             
                    try {                        
                        var result = JSON.parse(data)                               
                        console.log(result)    
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
                                                    +"<td class='text-center'>" + e.nb_actes_agent +"</td>"
                                                    +"<td class='text-center'>" + e.percent_ech_audit + "</td>"                                        
                                                    +"<td class='text-center'>" + e.nb_act_ech + "</td>"                                        
                                                    +"<td class='text-center'>" + e.date_audit + "</td>"                                        
                                                    +"<td class='text-center'>" + e.date_fin_audit + "</td>"                                        
                                                    +"<td class='text-center'>" + e.login + "</td>"                                        
                                                    +"<td class='text-center'>" + e.agent_traitement + "</td>"                                        
                                                    +"<td class='text-center'> ... </td>"                                        
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
    // ---------------------------------------------        

    // initialisation des déclencheurs
    audit_infos.forEach((a) => {

        $(`a[href="#${a.selector}"`).on("click",async () => {
            d = await getLotAuditLot(a.selector)   
            a.list_lots = JSON.parse(d)[1] 
        })            
    });

    (async () => {
       d = await getLotAuditLot(audit_infos[0].selector)                 
       audit_infos[0].list_lots = JSON.parse(d)[1]       
    })();
})