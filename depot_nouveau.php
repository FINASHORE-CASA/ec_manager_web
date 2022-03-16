<?php
require_once "is_connect.php";
require_once "./config/checkConfig.php";

$date_gen = date("Y-m-d");

$tomeregistre_columns = [
  "id_tome_registre", "sign_procureur_premier_page", "sceau_procureur", "jdate_sign_procu_greg", "mdate_sign_procu_greg", "adate_sign_procu_greg", "jdate_sign_procu_hegire", "mdate_sign_procu_hegire", "adate_sign_procu_hegire", "preimprime", "num_tome", "nbre_page", "nbre_acte_naissance", "nbre_acte_deces", "nbre_acte_mariage", "nbre_acte_divorce", "num_premier_acte", "num_dernier_acte", "txt_fermeture", "sign_oec", "sceau_oec", "existe_tab_annuel", "id_procureur", "id_officier", "jd_cloture_g", "md_cloture_g", "ad_cloture_g", "id_registre", "affecte", "status", "utilisateur_creation", "date_creation", "utilisateur_modification", "date_modification", "id_bureau", "id_commune", "ancien_commune", "ancien_bureau", "sign_procureur_dernier_page", "jd_sign_oec_h", "md_sign_oec_h", "ad_sign_oec_h", "jd_sign_oec_g", "md_sign_oec_g", "ad_sign_oec_g", "jd_ouverture_g", "md_ouverture_g", "ad_ouverture_g", "jd_cloture_h", "md_cloture_h", "ad_cloture_h", "jd_ouverture_h", "md_ouverture_h", "ad_ouverture_h", "langtomeregistre", "observation", "nbre_acte_naissance_nn_inclus", "nbre_acte_deces_nn_inclus", "nbre_acte_mariage_nn_inclus", "nbre_acte_divorce_nn_inclus", "numeros_actes_naissance_nn_inclus", "numeros_actes_deces_nn_inclus", "numeros_actes_mariage_nn_inclus", "numeros_actes_divorce_nn_inclus", "nbre_correction_niv_un", "nbre_correction_niv_deux", "indice_num_tome"
];

$tomeregistre_columns_locked = [
  "", "id_lot", "id_tome_registre", "id_registre", "id_commune", "id_bureau", "utilisateur_modification", "utilisateur_creation", "id_procureur", "id_officier", "date_creation", "date_modification", "preimprime", "affecte", "status", "ancien_commune", "ancien_bureau", "nbre_correction_niv_un", "nbre_correction_niv_deux"
];
$tomeregistre_columns_long = ["", "txt_fermeture"];
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

<body id="page-top" idpage="Depot">

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
              <span style="color:<?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;"> DEPOT <i class="fa fa-angle-double-right" aria-hidden="true"></i> </span> NOUVEAU DEPÔT
            </h4>
          </div>
          <hr />

          <!-- Content Row -->
          <div class="row">
            <div class="col-xl-8 mt-4 mb-4">
              <div class="card shadow mb-4">
                <form method="post" action="#">
                  <div class="card-header py-3" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                    <h6 class="m-0 font-weight-bold text-white"> Liste des lots à déposer</h6>
                  </div>
                  <div class="card-body">
                    <div id="form-idlot-field" class="row">
                      <div class="form-group col-md-4">
                        <div class="form-group">
                          <textarea class="form-control" id="text-list-lot" rows="9" style="align-content:center; overflow:auto;">
                                </textarea>
                        </div>
                      </div>
                      <div class="col-md-8">
                        <div class="d-flex justify-content-center mt-5">
                          <div id="txt-nb-lot" style="border: 1px solid gray;border-radius:100%;padding:35px;font-size:27px;">00</div>
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                          <p id="txt-nb-lot-notif" text-std="Le Contrôle sera effectué sur ces lots"> </p>
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
                    <button type="submit" class="btn btn-dark" style="background:  <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;" id="btn-controle">
                      Extraction <span class="badge badge-success" style="font-size:15px;border-radius:100%;padding:5px;"><i class="fas fa-download"></i> </span>
                    </button>
                  </div>
                </form>
              </div>
            </div>

            <div class="col-xl-4 mt-4 mb-4">
              <div class="card shadow">
                <div class="card-header py-3" style="background:white;">
                  <h6 class="m-0 font-weight-bold" style="color:  <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                    Lots disponibles <span id="indic-lot-dispo" class="badge badge-success"> 0 </span>
                  </h6>
                </div>
                <div class="card-body">
                  <div class="row d-flex justify-content-center">
                    <div class="form-group">
                      <textarea class="form-control" id="text-list-lot-dispo" rows="10" style="align-content:center; overflow:auto;display:none;" readonly="true">
                      </textarea>
                    </div>
                  </div>
                </div>
                <div class="card-footer  bg-success" style="display: none;" id="indic-termine">
                  <div class="d-flex justify-content-center" style="font-size: 15px;color:white;">
                    Terminé <i class="fas fa-check-double" style="margin-left:12px;margin-top:2px;"></i>
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
      <!-- version 1.0.1 -->
      <script src="js/ajax/sheetApi/getLotDispo.js?version=1.0.1"></script>

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