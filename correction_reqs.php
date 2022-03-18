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
  <script src="js/ajax/saisi/recherche_actes_reqs.js"></script>

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

</body>

</html>
