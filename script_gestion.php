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

  <title> <?= $app_name ?> </title>
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
              <span style="color:<?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;"> GESTION ECM <i class="fa fa-angle-double-right" aria-hidden="true"></i> </span> SCRIPTS DE GESTION
            </h4>
          </div>
          <hr />

          <!-- ------------------------------------------------------------------------ -->
          <div class="row pl-5">
            <div class="form-inline">
              <label for="bd_extra" class="mr-3"> <strong> 1 </strong> - Ajout des fonctions Utiles : </label>
              <img id="loader-script-1" src="./img/loader.gif" alt="loader wait" style="height: 50px;width:50px;padding:10px;display:none;" />
              <button id="btn-script-ultiles" type="submit" class="btn btn-dark" style="background: transparent;color:black;border: 1px solid rgba(0,0,0,0.1);box-shadow: 1px 1px 5px rgba(0,0,0,0.2);">
                Lancer <span class="badge badge-success" style="font-size:15px;border-radius:100%;padding:5px;"> <i class="fas fa-check-double"></i> </span>
              </button>
            </div>
          </div>
          <hr />
          <!-- ------------------------------------------------------------------------ -->


          <!-- ------------------------------------------------------------------------ -->
          <div class="row pl-5">
            <div class="form-inline">
              <label for="bd_extra" class="mr-3"> <strong> 2 </strong> - Préparation base de données acceptés : </label>
              <input type="file" id="file_upload_acceptes" style="display: none;" accept=".csv" />
              <input id="chemin_source" class="form-control mr-2" type="text" name="" placeholder="chemin bd source">
              <i class="fa fa-arrow-right mr-2" aria-hidden="true"></i>
              <input id="chemin_final" class="form-control mr-3" type="text" name="" placeholder="chemin bd final">
              <button id="btn-import-accepte" type="submit" class="btn btn-dark mr-1" style="background: transparent;color:black;border: 1px solid rgba(0,0,0,0.1);box-shadow: 1px 1px 5px rgba(0,0,0,0.2);">
                <i class="fa fa-upload text-primary" aria-hidden="true"></i>
              </button>
              <img id="loader-script-2" src="./img/loader.gif" alt="loader wait" style="height: 50px;width:50px;padding:10px;display:none;" />
              <button id="btn-script-prepare_bd_accepte" type="submit" class="btn btn-dark" style="background: transparent;color:black;border: 1px solid rgba(0,0,0,0.1);box-shadow: 1px 1px 5px rgba(0,0,0,0.2);" disabled>
                Lancer <span class="badge badge-success" style="font-size:15px;border-radius:100%;padding:5px;"> <i class="fas fa-check-double"></i> </span>
              </button>
            </div>
          </div>
          <div class="ml-5">
            <i> ( nomLivraison , lot ) </i>
          </div>
          <hr />
          <!-- ------------------------------------------------------------------------ -->

          <!-- ------------------------------------------------------------------------ -->
          <div class="row pl-5">
            <div class="form-inline">
              <label for="bd_extra" class="mr-3"> <strong> 3 </strong> - Encryter mot de passe automatique </label>
              <img id="loader-script-3" src="./img/loader.gif" alt="loader wait" style="height: 50px;width:50px;padding:10px;display:none;" />
              <button id="btn-script-encrypt-mdp" type="button" class="btn btn-dark" style="background: transparent;color:black;border: 1px solid rgba(0,0,0,0.1);box-shadow: 1px 1px 5px rgba(0,0,0,0.2);">
                Encrypter <span class="badge badge-success" style="font-size:15px;border-radius:100%;padding:5px;"> <i class="fas fa-lock"></i> </span>
              </button>
            </div>
          </div>
          <!-- ------------------------------------------------------------------------ -->

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
  <script src="js/jquery.csv.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="vendor/chart.js/Chart.min.js"></script>
  <script src="js/owner/set_side_bar.js"></script>
  <script src="js/owner/page_indicateur.js"></script>
  <script src="js/ajax/srcipt_utiles/add_bd_fonctions.js" type="text/javascript"></script>
  <script src="js/ajax/srcipt_utiles/prepare_bd_accepte.js?v=1.0.2" type="text/javascript"></script>
  <script src="js/ajax/srcipt_utiles/encrypt_all_mdp.js" type="text/javascript"></script>

</body>

</html>