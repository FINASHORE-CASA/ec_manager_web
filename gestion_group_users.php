<?php
require_once "is_connect.php";
require_once "./config/checkConfig.php";

$date_gen = date("Y-m-d");

$liste_roles = [
  "validation_lot", "transfert_lot", "stats_page", "split_bd", "script_gestion", "saisie_controle_acte_lot", "saisie_controle_acte_lot_auto", "purge_lot", "livraison_extraction_stats", "initialisation_lot",
  "gestion_users", "gestion_pref_setting", "gestion_impotation_setting", "gestion_group_users", "gestion_db_setting",
  "division_lot", "correction_reqs", "correction_acte", "controle_oec_popf", "controle_inventaire_liv", "controle_general_liv", "actioniec_controle_unitaire"
]
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
  <link href="css/bootstrap-select.min.css" rel="stylesheet" />

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

        <!-- Modal Suppression -->
        <div class="modal fade" id="SupUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header" style="background:<?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;color:white;">
                <h5 class="modal-title" id="exampleModalLabel"> Confirmez la suppression </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body"> Voulez vous vraiment supprimer ce groupe utilisateur ? </div>
              <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                <button id="btn-sup-confirm" id-group-user="" class="btn btn-danger" href="#"> Confirmer </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal User -->
        <div class="modal fade" id="UserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                <h5 class="modal-title" id="exampleModalLabel" style="color: white;"> Gestion Group Utilisateur </h5>
                <button type="button" class="close btn-form-modal-cancel text-danger" data-dismiss="modal" aria-label="Close">
                  <i class="fas fa-times-circle" style="font-size:16px;"></i>
                </button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                    <form class="mt-2">
                      <h6 style="color: black;"> Formulaire Groupe Utilisateur </h6>
                      <hr />
                      <div class="row">
                        <div class="form-group col-md-12">
                          <label for="field-Group"> Nom Group </label>
                          <input type="text" class="form-control" id="field-Group" aria-describedby="field-Group" placeholder="" />
                        </div>
                      </div>
                      <hr />
                      <div class="row">
                        <div class="form-group col-md-12">
                          <label for="field-Roles"> Roles </label>
                          <label for="field-Roles"> listes champs : </label>
                          <select class="selectpicker" id="list_roles" name="list_roles" width="100%" multiple>
                            <optgroup label="Roles">
                              <?php
                              foreach ($liste_roles as $value) {
                                echo '<option>' . $value . '</option>';
                              }
                              ?>
                            </optgroup>
                          </select>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-form-modal-cancel" data-dismiss="modal"> Annuler</button>
                <button id="form-update-save" id-group-user="0" type="button" class="btn btn-primary" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;"> Enregistrer <i class="far fa-save ml-1"></i></button>
              </div>
            </div>
          </div>
        </div>

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <h4 class="h6 mb-0 text-dark-800">
              <span style="color<?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;"> GESTION ECM <i class="fa fa-angle-double-right" aria-hidden="true"></i> </span> GESTION UTILISATEUR
            </h4>
          </div>
          <hr />

          <!-- Content Row -->
          <div class="row mt-3">
            <div class="col-md-12">
              <div>
                <!-- Traitement Image Vide -->
                <div class="card shadow mb-4 tab-pane active" id="ImageVide">
                  <div class="card-header py-3" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                    <a href="#" id="btn-add-group-user" class="btn btn-default float-right" style="color:white;border:1px solid white;" data-toggle='modal' data-target='#UserModal' id-group-user="0">
                      ajouter <i class="fas fa-users"></i>
                    </a>
                    <h6 class="m-0 font-weight-bold text-white"> Liste Groupe utilisateurs </h6>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-bordered" id="dataTableGroupUsers" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th> Group </th>
                            <th> Nombre Roles </th>
                            <th> Date Création </th>
                            <th> Date dernière Modif </th>
                            <th> Modif. </th>
                            <th> Supp. </th>
                          </tr>
                        </thead>
                        <tbody id="TableGroupUsers">

                        </tbody>
                      </table>
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
  <script src="js/bootstrap-select.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="js/demo/datatables-demo.js"></script>

  <!-- Page level custom scripts -->
  <script src="vendor/chart.js/Chart.min.js"></script>
  <script src="js/owner/set_side_bar.js"></script>
  <script src="js/owner/page_indicateur.js"></script>
  <script src="js/owner/gestion_group_users.js?version=1.0.1"></script>

</body>

</html>