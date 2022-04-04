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
    
    $('#dataTableUsers').on('draw.dt', function () {
        initBtnUser();                           
    });


    var initBtnUser = function()
    {        
        var btnEdit = $(".btn-edit");
        var btnDel = $(".btn-del");

        btnEdit.on("click",function()
        {
            // get information
            $("#form-user-loader").css("display","inherit")
            $("#form-user-fields").css("display","none")

            // initialisation des champs             
            $("#field-Name").val("");
            $("#field-FirstName").val("");
            $("#field-TypeGrant").val("");
            $("#field-Login").val("");
            $("#field-Password").val("");    
            $("#field-PasswordOrigin").val("");                

            // Récupération de l'Id du click
            var data1 = {
                id_user: $(this).attr("id-user"),
            }          

            $("#form-update-save").attr("id-user",$(this).attr("id-user"));                       

            // Récupération des informations du user
            $.post(HostLink+'/proccess/ajax/gestion_user/get_user.php',   // url
                { myData: JSON.stringify(data1) }, // data to be submit
                function(data, status, jqXHR) 
                {
                    var result = JSON.parse(data);                               
                    
                    if(result[0] == "success")
                    {
                        // Remplissage des champs du formulaire 
                        $("#field-Name").val(result[1].name);
                        $("#field-FirstName").val(result[1].first_name);
                        $("#field-TypeGrant").val(result[1].type_grant);
                        $("#field-Login").val(result[1].login);
                        $("#field-Password").val("000000");
                        $("#field-PasswordOrigin").val(result[1].password);
                    }
                    else
                    {
                        console.log('message error : ' + result);
                        console.log(result);
                    }                    
                    $("#form-user-loader").css("display","none")
                    $("#form-user-fields").css("display","inherit")
                });
        });

        btnDel.on("click",function()
        {
            $("#btn-sup-confirm").attr("id-user",$(this).attr("id-user"));                                                                                                                       
        });
    };    

    $("#btn-sup-confirm").on("click",function()
    {
        // Récupération de l'Id du click
        var data1 = {
            id_user: $(this).attr("id-user"),
        }          

        // Récupération des informations du user
        $.post(HostLink+'/proccess/ajax/gestion_user/del_user.php',   // url
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
            name: $("#field-Name").val(),
            first_name:$("#field-FirstName").val(),
            type_grant:$("#field-TypeGrant").val(),
            login:$("#field-Login").val(),
            password:$("#field-Password").val(),
            passwordOrigin:$("#field-PasswordOrigin").val(),
            id_user:$(this).attr("id-user")
        }   

        if($(this).attr("id-user") == "0")
        {
            //Appel Ajax d'ajout des informations
            $.post(HostLink+'/proccess/ajax/gestion_user/add_user.php',   // url
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
        else
        {
            //Appel Ajax d'update des informations
            $.post(HostLink+'/proccess/ajax/gestion_user/update_user.php',   // url
            { myData: JSON.stringify(data1) }, // data to be submit
                function(data, status, jqXHR) 
                {
                    console.log(data);
                    var result = JSON.parse(data);                               
                    
                    if(result[0] == "success")
                    {
                        // success callback                    
                        $(`#UserRow${data1.id_user} td:eq(0)`).html(result[1].name);
                        $(`#UserRow${data1.id_user} td:eq(1)`).html(result[1].first_name);
                        $(`#UserRow${data1.id_user} td:eq(2)`).html(result[1].name_group);
                        $(`#UserRow${data1.id_user} td:eq(3)`).html(result[1].date_creat);
                        $(`#UserRow${data1.id_user} td:eq(4)`).html(result[1].date_last_up);                   
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

    $("#btn-add-user").on("click",function()
    {
        // Restauration des champs du formulaire 
        $("#form-user-loader").css("display","none")
        $("#form-user-fields").css("display","inherit")
        $("#field-Name").val("");
        $("#field-FirstName").val("");
        $("#field-TypeGrant").val("1");
        $("#field-Login").val("");
        $("#field-Password").val("");        
        $("#field-PasswordOrigin").val("");    
        $("#form-update-save").attr("id-user","0");
    });

    // Recherche des données    
    var getData = function()
    { 
        $("#TableUsers").html('<tr><td colspan="7" class="text-center"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i></td></tr>')
        // Traitement Image Vide 
        $.get(HostLink+'/proccess/ajax/gestion_user/get_users.php')
         .done(function(data)
         {            
            var result = JSON.parse(data);  

            // injection des données                             
            htmlDataTable = "";    
            result[1].forEach(e => {                            
                htmlDataTable += "<tr id='UserRow"+ e.id_user +"'><td>" + e.name + "</td><td>" + e.first_name +  "</td><td>" + e.name_group + "</td><td>" + e.date_creat
                                        + "</td><td>" + e.date_last_up + "</td>" 
                                        + "<td class='text-center'> <a href='#' class='btn-edit' id-user='"+ e.id_user +"' style='color:gray;' data-toggle='modal' data-target='#UserModal'> <i class='fas fa-highlighter'></i> </a></td>" 
                                        + "<td class='text-center'> <a href='#' class='btn-del' id-user='"+ e.id_user +"' style='color:red;' data-toggle='modal' data-target='#SupUserModal'> <i class='far fa-times-circle'></i> </a></td></tr>";
            });
            $("#TableUsers").html("")
            $("#dataTableUsers").dataTable().fnDestroy();
            $("#TableUsers").html(htmlDataTable);            
            initDataTable($("#dataTableUsers"));         
            $("#form-update-save").attr("id-user","0");
            initBtnUser();
         });               
    };

    getData();

});