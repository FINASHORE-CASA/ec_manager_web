<?php
    require_once "is_connect.php";
    require_once "./config/checkConfig.php";  
    require_once "./proccess/ajax/saisi/schema_acte.php";          

    $date_gen = date("Y-m-d");
?>
<!DOCTYPE html>
<html lang="fr">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>  <?=$app_name ?> </title>
  <link rel="icon" href="img/favicon.ico" />

  <!-- Custom fonts for this template -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <link rel="icon" href="img/favicon.ico" />
  
  <!-- Custom styles for this template -->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <!-- <link rel="stylesheet" href="vendor/click-tap-image/dist/css/image-zoom.css" /> -->

</head>

<body id="page-top" idpage="Saisie">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- intégration sidebar  -->
    <?php include('partial/sidebar.php') ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- intégration topbar  -->
        <?php include('partial/topbar.php') ?>

        <!-- Modal -->
        <?php include('partial/modal_form_acte_all.php') ?>
        
        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <h4 class="h6 mb-0 text-dark-800">
              <span style="color:<?=isset($main_app_color) ? $main_app_color : "#3b2106";?>;"> SAISIE <i class="fa fa-angle-double-right" aria-hidden="true"></i> </span>  CORRECTION ACTE 
            </h4>
          </div> 
          <hr />  

          <!-- Liste des onglets  -->
          <div>
            <nav class="nav nav-tabs">
              <a class="nav-tab-item nav-item nav-link active" href="#Traitement" style="color: black;"> Lots </a>
              <a class="nav-tab-item nav-item nav-link" href="#Resultat" style="color: black;"> Actes <span class="badge badge-dark ml-2" style="background:red;border-radius:100%;display: none;" id="notif-Resultat-bell"><i class="far fa-bell"></i></span></a>
            </nav>
          </div>        
          
          <!-- Content Row -->
          <div class="tab-content">
            <div class="tab-pane active"  id="Traitement">  
              <div class="row">
                <div class="col-xl-8 mt-4 mb-4">
                  <div class="card shadow mb-4">
                    <form method="post" action="#">                      
                      <div class="card-header py-3" style="background: <?=isset($main_app_color) ? $main_app_color : "#3b2106";?>;">
                        <h6 class="m-0 font-weight-bold text-white"> Entrer la requête </h6>
                      </div>
                      <div class="card-body">
                        <div id="form-idlot-field" class="row">
                          <div class="form-group col-md-12">
                                <div class="form-group">
                                  <textarea class="form-control" id="text-reqs" rows="10" 
                                            style="align-content:center; overflow:auto;background:rgba(0,0,0,0.1);color:black;">
                                  </textarea>
                                </div>
                          </div>
                        </div>
                        <div id="form-lot-loader" style="position: absolute;background:rgba(255, 255, 255,0.8);top:0;width:100%;left:0px;height:100%;display:none;z-index:10;">
                          <div class="d-flex justify-content-center" style="padding-top: 9em;">
                            <img src="./img/loader.gif" alt="loader wait" />
                          </div>
                          <div class="d-flex justify-content-center mt-3" style="color: black;">
                            <p> <b> Traitement en cours ... </b></p>
                          </div>
                        </div>
                      </div>
                      <div class="card-footer" id="form-idlot-footer">                              
                          <button class="btn btn-secondary" type="reset" id="btn-reset-controle" data-dismiss="modal">Annuler</button>                      
                          <button type="submit" class="btn btn-dark" style="background:  <?=isset($main_app_color) ? $main_app_color : "#3b2106";?>;" id="btn-search_acte">
                            Exécuter <span class="badge badge-primary"  style="font-size:15px;border-radius:100%;padding:7px;background:white;"> <i class="fas fa-play" style="font-size:12px;color: <?=isset($main_app_color) ? $main_app_color : "#3b2106";?>;"></i> </span>
                          </button>
                      </div>
                    </form>                  
                  </div>
                </div>
              </div>                
            </div> 

            <div class="tab-pane" id="Resultat" >     

              <div id="alert-container">
                
              </div>

              <div id="resultat_data" class="row mt-3">                
                <div class="col-md-12"> 
                  <!-- Correction des actes  -->
                  <div class="card shadow mb-4 tab-pane active" id="ListeActes">
                    <div class="card-header py-3" style="background: <?=isset($main_app_color) ? $main_app_color : "#3b2106";?>;">
                      <h6 class="m-0 font-weight-bold text-white"> Liste des Actes </h6>
                    </div>
                    <div class="card-body" id="table_container">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.container-fluid -->        
      </div>
      <!-- End of Main Content -->

      <!-- intégration footer  -->
      <?php include('partial/footer.php') ?>

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- intégration modal déconnexion  -->
  <?php include('partial/modal_logout.php') ?>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->  
  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="js/demo/datatables-demo.js"></script>  
  <!-- <script src="vendor/click-tap-image/dist/js/image-zoom.min.js"></script> -->

  <!-- Page level custom scripts -->
  <script src="vendor/chart.js/Chart.min.js"></script>
  <script src="js/owner/set_side_bar.js"></script>
  <script src="js/owner/page_indicateur.js"></script>
  <script src="js/owner/count_lot.js"></script>
  <!-- <script src="js/ajax/saisi/recherche_actes_reqs.js"></script> -->

  <script>
    $(document).ready(function(e)
    {     

      $('.nav-tab-item').click(function (e) 
          {        
            e.preventDefault()
            $(this).tab('show')
          }).on('shown.bs.tab', function (e) 
            {
              $('#actif').text($(e.target).text())
              $('#precedent').text($(e.relatedTarget).text())
            })
    });
  </script>

  <script>
    $(document).ready(function() {

        // Selection des indicateurs 
        var btnSearchActe = $("#btn-search_acte")
            ,formLoader = $("#form-lot-loader")
            ,textListLot = $("#text-reqs")
            ,txtControleNotif = $("#txt-nb-lot-notif")
            ,notifResultat = $("#notif-Resultat-bell")
            ,ResultatData = $("#resultat_data")
            ,alertBox = $("#alert_box");    

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
            $("#img-block").html("<p class='d-flex justify-content-center' style='margin-top:250px;'> Image Introuvable </p>");         
        });
        
        $("#form-update-save").on("click",function(e){            
            // Récupération de l'Id du click
            var data1 = {}   

            $("#ActeModal .form-control").each((i,el) => 
            {
                data1[el.id.replace("field-","")] = el.value.trim();            
            }); 

            $.post('./proccess/ajax/saisi/update_acte_all.php',   // url
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
                      $("#image2").css("display","none");
                      $(this).css("color","black");
                      $("#img-switch2").css("color","gray");
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
                      $(this).css("color","black");
                      $("#img-switch1").css("color","gray");
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

                    $("#field-Id_lot").html("");            

                    $.post('./proccess/ajax/saisi/recup_acte_all_req.php',   // url
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
                                                htmlContentImg += "<img id='image"+ i + "' class='img-fluid img-thumbnail' style='height:auto;width:auto;"+ ((i == 2) ? "display:none;" : "") +"' src='"+ result[3] +"\\" + result[1].id_acte + "_" +  e + "' alt='"+  e +"'/>";                 
                                            }  
                                            i++;
                                        });
                                        $("#img-block").html(htmlContentImg);
                                        $("#block-img-change").html("<a id='img-switch1' class='img-switch' href='#' ownid='1' style='color: black;font-size:20px;text-decoration:none;'> <i class='far fa-dot-circle'></i></a>"
                                                                +  "<a id='img-switch2' class='img-switch' href='#' ownid='2' style='color: gray;font-size:20px;text-decoration:none;'> <i class='far fa-dot-circle ml-1'></i></a>");
                                    }
                                    else
                                    {
                                        $("#img-block").html("<img id='image1' class='img-fluid img-thumbnail' style='height:auto;width:auto;' src='"+ result[3] +"\\" + result[1].id_acte + "_" + result[1].imagepath +"' alt='"+  result[1].imagepath +"'/>");                                                 
                                        $("#block-img-change").html("<a id='img-switch1' class='img-switch' href='#' ownid='1' style='color: black;font-size:20px;text-decoration:none;'> <i class='far fa-dot-circle'></i></a>");
                                    }
                                }           
                                else
                                {
                                    $("#img-block").html("<p class='d-flex justify-content-center' style='margin-top:250px;'> Image Introuvable </p>");
                                }

                                startSwitchImage();
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
                }              

                // Traitement Image Vide 
                $.post('./proccess/ajax/saisi/exec_req_acte.php',   // url
                    { myData: data1.reqs,contentType: "text/plain; charset=utf-8", }, // data to be submit
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

  </script>

</body>

</html>
