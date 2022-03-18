<?php
    require_once "is_connect.php";
    require_once "./config/checkConfig.php";  

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

<body id="page-top" idpage="GestionECM">

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

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <h4 class="h6 mb-0 text-dark-800">
              <span style="color:<?=isset($main_app_color) ? $main_app_color : "#3b2106";?>;"> GESTION ECM <i class="fa fa-angle-double-right" aria-hidden="true"></i> </span> GESTION BD
            </h4>
          </div> 
          <hr/>  
          
          <!-- Content Row -->  
            <div class="row mt-3">  
              <div class="col-sm-12"> 
                <div>
                  <!-- Traitement Image Vide -->
                  <div class="card shadow mb-4 tab-pane active" id="ImageVide">
                    <div class="card-header py-3" style="background: <?=isset($main_app_color) ? $main_app_color : "#3b2106";?>;">
                        <a href="#" id="btn-add-db" class="float-right" style="color:white;">
                          <i class="fa fa-database" aria-hidden="true"></i>
                        </a>
                        <h6 class="m-0 font-weight-bold text-white"> Liste Base de données </h6>                        
                    </div>
                    <div class="card-body" style="height:500px;overflow-y: auto;">
                      <div class="row mb-4 d-flex justify-content-end mr-4">
                        <div>
                          <div class="input-group" style="width: 250px;">
                            <input class="form-control" type="text" name="" 
                                  style="border-right:none;"
                                  id="search_db"
                                  placeholder="recherche..." aria-label="" aria-describedby="my-addon"/>
                            <div class="input-group-append">
                              <span class="input-group-text" id="my-addon" style="background: transparent;border-left:none;">
                                  <i class="fa fa-search" style="color:<?=isset($main_app_color) ? $main_app_color : "#3b2106";?>;" aria-hidden="true"></i> 
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div id="list-db">   
                        
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

  <!-- Page level custom scripts -->
  <script src="vendor/chart.js/Chart.min.js"></script>
  <script src="js/owner/set_side_bar.js"></script>
  <script src="js/owner/page_indicateur.js"></script>
  <script src="js/owner/gestion_db.js"></script>

</body>

</html>
