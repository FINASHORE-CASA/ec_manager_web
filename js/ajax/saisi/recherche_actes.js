$(document).ready(function() {

    var HostLink = window.location.href.split("/")[0] +"//"+ window.location.href.split("/")[2]+ "/" +window.location.href.split("/")[3];
    HostLink = HostLink.includes(".php") ? "." : HostLink;

    // Selection des indicateurs 
    var btnSearchActe = $("#btn-search_acte")
        ,formLoader = $("#form-lot-loader")
        ,textListLot = $("#text-list-lot")
        ,txtControleNotif = $("#txt-nb-lot-notif")
        ,notifResultat = $("#notif-Resultat-bell")
        ,ResultatData = $("#resultat_data")
        ,alertBox = $("#alert_box");    

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

    $('#dataTableListeActes').on('draw.dt', function () {
        startEditActe();                           
    });

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

        $.post(HostLink+'/proccess/ajax/saisi/update_acte_all.php',   // url
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
    var startEditActe = function() 
    {
        var btnEdit = $(".btn-edit");

        // Remplissage des informations de l'acte
        btnEdit.on("click",function()
        {
            // Récupération de l'Id du click
            var data1 = {
                id_acte: $(this).attr("idActe"),
                imagepath: $("#ActeRow"+$(this).attr("idActe")+" td[name='imagepath']").text().trim(),
                id_lot: $("#ActeRow"+$(this).attr("idActe")+" td:eq(1)").text().trim()
            }     
            $("#form-acte-loader").css("display","block");
            console.log(data1)

            // Récupération des tds    
            const listTd = $("#ActeRow"+data1.id_acte+" td");                 

            $("#field-Id_lot").html(data1.id_lot);            
            listTd.each((i,td) => 
            {
                const champ = td.getAttribute("name");
                $("#field-"+champ).val(td.innerText.trim());                                                                                                                                  
            });

            $.post(HostLink+'/proccess/ajax/saisi/recup_acte_all.php',   // url
                { myData: JSON.stringify(data1) }, // data to be submit
                function(data, status, jqXHR) 
                {
                    var result = JSON.parse(data);                               
                    
                    if(result[0] == "success")
                    {                        
                        console.log(result[5]);

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
                        $("#form-acte-loader").css("display","none");  
                    }
                    else
                    {
                        console.log('message error : ' + result);
                        console.log(result);
                    } 
                }
            ); 
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
        var nbLot = countNbLot(textListLot.val());
        if(nbLot == 0)
        {
            txtControleNotif.css("color","red");
            txtControleNotif.text("aucun lot renseigné.");
        }
        else
        {
            // hide previous result
            formLoader.css("display","inherit");
            notifResultat.fadeOut("fast"); 
            ResultatData.fadeOut("fast");    
            $("#dataTableListeActes").dataTable().fnDestroy()

            // traitement des lots             
            var data1 = {
                id_lot: textListLot.val().trim().replace(/[\n\r]/g,', '),
            }              

            // Traitement Image Vide 
            $.post(HostLink+'/proccess/ajax/saisi/liste_acte_lot.php',   // url
                { myData: JSON.stringify(data1) }, // data to be submit
                    function(data, status, jqXHR) 
                    {
                        var result = JSON.parse(data);                               
                        
                        if(result[0] == "success")
                        {
                            // success callback
                            // paramétrage de l'affichage
                            notifResultat.fadeIn("slow");
                            ResultatData.fadeIn("slow");
                                                    
                            // injection des données                             
                            htmlDataTable = "";    
                            result[1].forEach(e => { 
                                                                
                                htmlDataTable += "<tr id='ActeRow"+ e.id_acte +"'>"
                                +'<td class="text-center"> <a href="#" class="btn-edit" idActe="'+ e.id_acte +'" style="color:gray;" data-toggle="modal" data-target="#ActeModal"><i class="fas fa-highlighter"></i></a></td>'
                                +'<td> '+e.id_lot +'</td>'
                                
                                result[2].forEach(nomchamp => 
                                {
                                    if(Object.keys(e).some(e1=> {return e1 == nomchamp['cname']}))
                                    {
                                       let elValue =  Object.keys(e).filter(e1=> {return e1 == nomchamp['cname']})[0];                                       
                                       htmlDataTable += '<td name="'+elValue+'"> '+ e[elValue] +'</td>';
                                    }
                                });                                
                            });
                            
                            $("#TableListeActes").html(htmlDataTable);
                            initDataTable($('#dataTableListeActes'));                                              
                            
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