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
  <link href="css/modal-fullscreen.css" rel="stylesheet" />

  <!-- Custom styles for this page -->
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top" idpage="GestionStats">

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
              <span style="color:<?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;"> GESTION STATS <i class="fa fa-angle-double-right" aria-hidden="true"></i> </span> STATS CONTROLE
            </h4>
          </div>
          <hr />

          <!-- Liste des onglets  -->
          <div>
            <nav class="nav nav-tabs">
              <a class="nav-tab-item nav-item nav-link active" href="#Traitement" style="color: black;"> Liste Lots </a>
              <a class="nav-tab-item nav-item nav-link" href="#Resultat" style="color: black;"> Données Stats <span class="badge badge-dark ml-2" style="background:red;border-radius:100%;display: none;" id="notif-Resultat-bell"><i class="far fa-bell"></i></span></a>
            </nav>
          </div>

          <!-- Content Row -->
          <div class="tab-content">
            <div class="tab-pane active" id="Traitement">
              <div class="row">
                <div class="col-xl-8 mt-4 mb-4">
                  <div class="card shadow mb-4">
                    <form method="post" action="#">
                      <div class="card-header py-3" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                        <h6 class="m-0 font-weight-bold text-white"> Liste des lots à générer</h6>
                      </div>
                      <div class="card-body">
                        <div id="form-idlot-field" class="row mt-2">

                          <div class="form-group col-md-4">
                            <div class="input-group col-md-12 col-lg-12">
                              <div class="input-group-prepend">
                                <button type="button" class="btn btn-outline-secondary" style="z-index:inherit"> Du : </button>
                              </div>
                              <input type="date" class="form-control date_debut" id="date_gen_deb_acte" name="date_gen_deb" value="<?= $date_gen ?>" aria-label="Text input with segmented dropdown button" required />
                            </div>
                          </div>
                          <div class="form-group col-md-4">
                            <div class="input-group col-md-12 col-lg-12">
                              <div class="input-group-prepend">
                                <button type="button" class="btn btn-outline-secondary" style="z-index:inherit"> Au : </button>
                              </div>
                              <input type="date" class="form-control date_fin" id="date_gen_fin_acte" name="date_gen_fin" value="<?= $date_gen ?>" aria-label="Text input with segmented dropdown button" required />
                            </div>
                          </div>

                        </div>
                        <div id="form-lot-loader" style="position: absolute;background:rgba(255, 255, 255,0.8);top:0;width:100%;left:0px;height:100%;display:none;z-index:10;">
                          <div class="d-flex justify-content-center" style="padding-top: 6em;">
                            <img src="./img/loader.gif" alt="loader wait" style="height: 50px;" />
                          </div>
                          <div class="d-flex justify-content-center mt-3" style="color: black;">
                            <p> <b> Traitement en cours ... </b></p>
                          </div>
                        </div>
                      </div>
                      <div class="card-footer" id="form-idlot-footer">
                        <button class="btn btn-secondary" type="reset" id="btn-reset-controle" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-dark" style="background:  <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;" id="btn-controle">
                          Générer les fichiers <span class="badge badge-success" style="font-size:15px;border-radius:100%;padding:5px;"><i class="fas fa-download"></i> </span>
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
                <div class="col-xl-4 mt-4 mb-4">
                  <div class="card shadow">
                    <div class="card-header py-3" style="background:white;">
                      <h6 class="m-0 font-weight-bold" style="color:  <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;"> Progression</h6>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <ol id="liste-indic">
                          <li class="mb-2" style="display: none;"> Stats Contrôle Unitaire <i class="fas fa-check text-success" style="margin-left:5px;font-size:20px;"></i> </li>
                          <li class="mb-2" style="display: none;"> Stats Mention Manquant <i class="fas fa-check text-success" style="margin-left:5px;font-size:20px;"></i> </li>
                        </ol>
                      </div>
                    </div>
                    <div class="card-footer  bg-success" style="display: none;" id="indic-termine">
                      <div class="d-flex justify-content-center" style="font-size: 15px;color:white;">
                        Terminé <i class="fas fa-check-double" style="margin-left:12px;margin-top:2px;"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <a id="download" href="#" type="downlaod" style="display:none;"> télécharger </a>
            <div class="tab-pane" id="Resultat">
              <div id="alert-container"></div>
              <div id="resultat_data" class="row mt-3" style="display: none;">
                <div class="col-md-12">
                  <div class="mb-3">
                    <nav class="nav nav-tabs">
                      <a class="nav-tab-item nav-item nav-link active" href="#StatsControleUnitaire" style="color: black;"> Stats Contrôle Unitaire <span class="badge badge-dark ml-2" style="background:red;border-radius:100%;" id="notif-Resultat-1"> 0 </span> <button id="StatsControleUnitaire_dl" class="text-dark ml-2" style="background-color: transparent;border:none;"> <i class="fa fa-download" aria-hidden="true"></i> </button></a>
                      <a class="nav-tab-item nav-item nav-link" href="#StatsManquantMention" style="color: black;"> Stats Mention Manquant <span class="badge badge-dark ml-2" style="background:red;border-radius:100%;" id="notif-Resultat-2"> 0 </span> <button id="StatsMentionManquant_dl" class="text-dark ml-2" style="background-color: transparent;border:none;"> <i class="fa fa-download" aria-hidden="true"></i> </button></a>
                    </nav>
                  </div>

                  <div class="tab-content">

                    <!-- Stats Contrôle Unitaire  -->
                    <div class="card shadow mb-4 tab-pane active" id="StatsControleUnitaire">
                      <div class="card-header py-3" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                        <h6 class="m-0 font-weight-bold text-white"> Stats Contrôle Unitaire </h6>
                      </div>
                      <div class="card-body">
                        <div class="table-responsive">
                          <table class="table table-bordered" id="dataTableStatsControleUnitaire" width="100%" cellspacing="0">
                            <thead>
                              <tr>
                                <th> Login </th>
                                <th> Lot </th>
                                <!-- <th> Nombre actes </th> -->
                                <th> Nombre actes Controlés </th>
                                <th> Date Controle </th>
                              </tr>
                            </thead>
                            <tbody id="TableStatsControleUnitaire">

                            </tbody>
                            <tfoot>
                              <th> Login </th>
                              <th> Lot </th>
                              <!-- <th> Nombre actes </th> -->
                              <th> Nombre actes Controlés </th>
                              <th> Date Controle </th>
                            </tfoot>
                          </table>
                        </div>
                      </div>
                    </div>

                    <!-- Stats Mention Manquant -->
                    <div class="card shadow mb-4 tab-pane" id="StatsManquantMention">
                      <div class="card-header py-3" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                        <h6 class="m-0 font-weight-bold text-white"> Stats Contrôle Unitaire </h6>
                      </div>
                      <div class="card-body">
                        <div class="table-responsive">
                          <table class="table table-bordered" id="dataTableStatsManquantMention" width="100%" cellspacing="0">
                            <thead>
                              <tr>
                                <th> Lot </th>
                                <th> Id_acte </th>
                                <th> Mention Acte </th>
                                <th> Mention Correcte </th>
                                <th> Date Controle </th>
                                <th> Login Agent </th>
                              </tr>
                            </thead>
                            <tbody id="TableStatsManquantMention">

                            </tbody>
                            <tfoot>
                              <th> Lot </th>
                              <th> Id_acte </th>
                              <th> Mention Acte </th>
                              <th> Mention Correcte </th>
                              <th> Date Controle </th>
                              <th> Login Agent </th>
                            </tfoot>
                          </table>
                        </div>
                      </div>
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
  <script src="js/panzoom.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="vendor/chart.js/Chart.min.js"></script>
  <script src="js/owner/set_side_bar.js"></script>
  <script src="js/owner/page_indicateur.js"></script>
  <script src="js/modal-fullscreen.js"></script>
  <script src="js/owner/count_lot.js"></script>
  <!-- next version -- 1.0.3   -->
  <script src="js/ajax/stats/stats_controle.js?version=1.0.4"></script>

  <script>
    $(document).ready(function(e) {
      $('.nav-tab-item').click(function(e) {
        e.preventDefault()
        $(this).tab('show')
      }).on('shown.bs.tab', function(e) {
        $('#actif').text($(e.target).text())
        $('#precedent').text($(e.relatedTarget).text())
      })
    });
  </script>

</body>

</html>