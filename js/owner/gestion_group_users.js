$(document).ready(function(e){

    var HostLink = window.location.href.split("/")[0] +"//"+ window.location.href.split("/")[2]+ "/" +window.location.href.split("/")[3];
    HostLink = HostLink.includes(".php") ? "." : HostLink;

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

    var initBtnUser = function()
    {        
        var btnEdit = $(".btn-edit");
        var btnDel = $(".btn-del");

        btnEdit.on("click",function()
        {
            // initialisation des champs             
            $("#field-Group").val("")
            $("#list_roles").val("") 
            $(".filter-option-inner-inner").html("Nothing selected")  

            // Récupération de l'Id du click
            var data1 = {
                id_type_grant:$(this).attr("id-group-user"),
            }          

            $("#form-update-save").attr("id-group-user",$(this).attr("id-group-user"));                       

            // Récupération des informations du user
            $.post(HostLink+'/proccess/ajax/gestion_user/get_group_user.php',   // url
                { myData: JSON.stringify(data1) }, // data to be submit
                function(data, status, jqXHR) 
                {
                    var result = JSON.parse(data);                               
                    if(result[0] == "success")
                    {
                        // Remplissage des champs du formulaire 
                        $("#field-Group").val(result[1].name_group)
                        $("#list_roles").val(result[1].list_role.split(","))
                        $(".filter-option-inner-inner").html(result[1].list_role != "" ? result[1].list_role.replace(","," ") : "Nothing selected")
                    }
                    else
                    {
                        console.log('message error : ' + result)
                        console.log(result)
                    }
                });
        });

        btnDel.on("click",function()
        {
            $("#btn-sup-confirm").attr("id-group-user",$(this).attr("id-group-user"));                                                                                                                       
        });
    };    

    $("#btn-sup-confirm").on("click",function()
    {
        // Récupération de l'Id du click
        var data1 = {
            id_type_grant: $(this).attr("id-group-user"),
        }          

        // Récupération des informations du user
        $.post(HostLink+'/proccess/ajax/gestion_user/del_group_user.php',   // url
            { myData: JSON.stringify(data1) }, // data to be submit
            function(data, status, jqXHR) 
            {
                var result = JSON.parse(data);                               
                
                if(result[0] == "success")
                {                    
                    // must add a box success display
                    getData();                               
                    $("#SupUserModal").modal("hide");
                }
                else
                {
                    console.log('message error : ' + result);
                    console.log(result);
                }
            });
    });

    $("#form-update-save").on("click",function()
    {
        // Récupération de l'Id du click
        var data1 = {
            id_type_grant:$(this).attr("id-group-user"),
            name_group: $("#field-Group").val(),
            list_role:$("#list_roles").val()
        }   

        if($(this).attr("id-group-user") == "0")
        {
            //Appel Ajax d'ajout des informations
            $.post(HostLink+'/proccess/ajax/gestion_user/add_group_user.php',   // url
            { myData: JSON.stringify(data1) }, // data to be submit
                function(data, status, jqXHR) 
                {
                    console.log(data);
                    var result = JSON.parse(data);                               
                    
                    if(result[0] == "success")
                    {
                        // success callback
                        // must add a box success display
                        getData();                                                                        
                        console.log('success : ' + result[1]);   
                        $("#UserModal").modal("hide");
                    }
                    else if(result[0] == "role_find")
                    {
                        alert("ce groupe existe déja !");
                    }
                    else
                    {
                        console.log('message error : ' + result);
                        console.log(result);
                    }
                }
            ); 
        }  
        else
        {
            //Appel Ajax d'update des informations
            $.post(HostLink+'/proccess/ajax/gestion_user/update_group_user.php',   // url
            { myData: JSON.stringify(data1) }, // data to be submit
                function(data, status, jqXHR) 
                {
                    console.log(data);
                    var result = JSON.parse(data);                                                   
                    
                    if(result[0] == "success")
                    {
                        // success callback
                        // must add a box success display
                        getData();                               
                        $("#UserModal").modal("hide");
                    }
                    else if(result[0] == "login_find")
                    {
                        alert("ce login existe déja !");
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

    $("#btn-add-group-user").on("click",function()
    {
        // Restauration des champs du formulaire 
        $("#field-Group").val("")
        $("#list_roles").val("") 
        $(".filter-option-inner-inner").html("Nothing selected")  
        $("#form-update-save").attr("id-group-user","0");
    });


    // Recherche des données    
    var getData = function()
    { 
        // Traitement Image Vide 
        $.get(HostLink+'/proccess/ajax/gestion_user/get_group_users.php')
         .done(function(data)
         {            
            var result = JSON.parse(data);  

            // injection des données                             
            htmlDataTable = "";    
            result[1].forEach(e => {                            
                htmlDataTable += "<tr><td>" + e.name_group + "</td><td>" + e.list_role.split(",").length + "</td><td>" + e.date_creat
                                        + "</td><td>" + e.date_modif + "</td>" 
                                        + "<td class='text-center'> <a href='#' class='btn-edit' id-group-user='"+ e.id_type_grant +"' style='color:gray;' data-toggle='modal' data-target='#UserModal'> <i class='fas fa-highlighter'></i> </a></td>" 
                                        + "<td class='text-center'> <a href='#' class='btn-del' id-group-user='"+ e.id_type_grant +"' style='color:red;' data-toggle='modal' data-target='#SupUserModal'> <i class='far fa-times-circle'></i> </a></td></tr>";
            });

            $("#dataTableGroupUsers").dataTable().fnDestroy();
            $("#TableGroupUsers").html(htmlDataTable);            
            initDataTable($("#dataTableGroupUsers"));         
            $("#form-update-save").attr("id-group-user","0");
            initBtnUser();
         });               
    };

    getData();

});