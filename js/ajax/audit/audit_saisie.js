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
})