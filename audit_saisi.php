<?php
require_once "is_connect.php";
require_once "./config/checkConfig.php";

$liste_champs_actes = ["num_acte", "annee_acte", "jd_naissance_h", "md_naissance_h", "ad_naissance_h", "jd_naissance_g", "md_naissance_g", "ad_naissance_g", "heure_naissance", "minute_naissance", "lieu_naissance", "jumeaux", "prenom_ar", "prenom_fr", "nom_ar", "nom_fr", "nom_marge_ar", "prenom_marge_fr", "nom_marge_fr", "prenom_marge_ar", "sexe", "id_nationlite", "decede_pere", "prenom_pere_ar", "prenom_pere_fr", "ascendant_pere_ar", "ascendant_pere_fr", "id_nationalite_pere", "id_profession_pere", "jd_naissance_pere_h", "md_naissance_pere_h", "ad_naissance_pere_h", "jd_naissance_pere_g", "md_naissance_pere_g", "ad_naissance_pere_g", "lieu_naissance_pere", "decede_mere", "prenom_mere_ar", "prenom_mere_fr", "ascendant_mere_ar", "ascendant_mere_fr", "id_nationalite_mere", "id_profession_mere", "jd_naissance_mere_h", "md_naissance_mere_h", "ad_naissance_mere_h", "jd_naissance_mere_g", "md_naissance_mere_g", "ad_naissance_mere_g", "lieu_naissance_mere", "adresse_residence_parents", "jd_etabli_acte_h", "ad_etabli_acte_h", "md_etabli_acte_h", "jd_etabli_acte_g", "ad_etabli_acte_g", "md_etabli_acte_g", "id_officier", "annee_acte_g", "lieu_naissance_fr", "lieu_naissance_pere_fr", "lieu_naissance_mere_fr", "adresse_residence_parents_fr", "sign_officier", "sceau_officier", "status_acte", "heure_etabli_acte", "minute_etabli_acte", "date_creation", "status_acteechantillon", "langue_acte", "id_ville_naissance", "id_ville_naissance_mere", "id_ville_naissance_pere", "id_ville_residence_parents", "date_statut_oec", "imagepath", "remarque"];
$liste_champs_deces = ["jd_deces_h", "md_deces_h", "ad_deces_h", "jd_deces_g", "md_deces_g", "ad_deces_g", "heure_deces", "minute_deces", "lieu_deces", "id_profession", "statutfamilialle", "lieuresidence", "lieuresidence_fr", "lieu_deces_fr", "lieu_residence_pere_ar", "lieu_residence_pere_fr", "lieu_residence_mere_ar", "lieu_residence_mere_fr", "id_ville_deces", "id_ville_adresse_mere", "id_ville_adresse_pere", "id_ville_adresse"];

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
  <link href="css/sb-admin-2.min.css" rel="stylesheet" />
  <link href="css/bootstrap-select.min.css" rel="stylesheet" />
  <link href="css/modal-fullscreen.css" rel="stylesheet" />

  <!-- Custom styles for this page -->
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" />
</head>

<body id="page-top" idpage="Audit">

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

        <!-- Formulaire Affectation Lot Modal -->
        <div class="modal fade" id="AgentAffectationAudit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                <h5 class="modal-title" id="AgentAuditLogin" style="color: white;"></h5>
                <div style="position:absolute;right:5px;">
                </div>
              </div>
              <div class="modal-body">
                <div id="formAffectAgentAudit" class="row">
                  <form class="mt-2" style="width: 100%;">
                    <input type="hidden" id="field-Id_user" value="<?= isset($_SESSION['user']) ? $_SESSION['user']->id_user : '' ?>" />
                    <div class="row m-3">
                      <div class="col-md-12">
                        <div id="list-lots-user" style="overflow:auto;max-height:200px;">

                        </div>
                        <hr />
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <textarea id="list-lot-a-aff" class="form-control" name="" rows="2"></textarea>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <button id="btn-valider-affectation" type="button" class="btn btn-success" style="width:100%;" type_audit="" id_user>
                          valider <i class="fa fa-check-circle" aria-hidden="true"></i>
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="row" id="AgentAuditAffectLoader">
                  <div class="d-flex justify-content-center" style="width: 100%;">
                    <div>
                      <i class="fa fa-spinner fa-spin" style="font-size:2rem;" aria-hidden="true"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Formulaire liste Lot Modal -->
        <div class="modal fade" id="listLotModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                <h5 class="modal-title" style="color: white;"> Liste lot <span id="list-all-lot-dispo-nb" class="badge badge-light ml-2"> 0 </span> </h5>
              </div>
              <div class="modal-body">
                <textarea id="list-all-lot-dispo" class="form-control" name="" rows="7" readonly="true"></textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <h4 class="h6 mb-0 text-dark-800">
              <span style="color:<?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;"> AUDIT <i class="fa fa-angle-double-right" aria-hidden="true"></i> </span> AUDIT SAISIE
            </h4>
          </div>
          <hr />

          <!-- lien de téléchargement -->
          <a id="download" href="#" type="download" style="display:none;"> télécharger </a>

          <!-- Content Row -->
          <div class="tab-content">
            <div class="row">
              <div class="col-xl-3 mt-4 mb-4">
                <div class="card shadow mb-4">
                  <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"> Liste Lot <span id="nb-liste-lot-agent" class="ml-2 badge badge-primary"> 0 </span> </h6>
                  </div>
                  <div class="card-body">
                    <div class="mb-4">
                      <input class="form-control" type="text" placeholder="rechercher" />
                    </div>
                    <div class="table-responsive" style="overflow:auto;max-height:250px;">
                      <table class="table" id="dataTableLotAgentDispo" width="100%" cellspacing="0">
                        <tbody id="TableAgentLotAgentDispo">

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <div class="card shadow mb-4">
                  <div class="card-body">
                    <div class="form-inline ml-3">
                      <label for="mode_ech"> % échantillonnage </label>
                      <input type="number" id="ech_value" class="form-control ml-3" id="mode_ech" style="width:70px;" step="5" value="20" />
                    </div>
                    <hr />
                    <div class="form-inline ml-3">
                      <label for="list_champs"> listes champs : </label>
                      <select class="selectpicker" id="list_champs" name="list_champs" width="100%" multiple>
                        <optgroup label="Acte">
                          <?php
                          foreach ($liste_champs_actes as $value) {
                            echo '<option>' . $value . '</option>';
                          }
                          ?>
                        </optgroup>
                        <optgroup label="Deces">
                          <?php
                          foreach ($liste_champs_deces as $value) {
                            echo '<option>' . $value . '</option>';
                          }
                          ?>
                        </optgroup>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="card shadow mb-4">
                  <div class="card-body">
                    <button class="btn btn-success" style="width:100%;"> Auditer <i class="fas fa-flag-checkered"></i> </button>
                  </div>
                </div>
              </div>

              <div class="col-xl-9 mt-4 mb-4">
                <div class="card shadow mb-4">
                  <form method="post" action="#">
                    <div class="card-header py-3" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                      <h6 class="m-0 font-weight-bold text-white"> Liste des actes
                        <!-- <button id="audit_saisie_dl" class="text-white float-right" style="background-color: transparent;border:none;"> <i class="fa fa-download" aria-hidden="true"></i> </button> -->
                      </h6>
                    </div>
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-bordered" id="dataTableAuditSaisi" width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th> Id lot </th>
                              <th> Commune </th>
                              <th> Bureau </th>
                              <th> Nombre acte </th>
                              <th> Date Saisie </th>
                            </tr>
                          </thead>
                          <tbody id="TableAuditSaisi">
                            <tr>
                              <td colspan="7" class="text-center" style="font-size:2rem;"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i></td>
                            </tr>
                          </tbody>
                          <tfoot>
                            <td> Id lot </td>
                            <td> Commune </td>
                            <td> Bureau </td>
                            <td> Nombre acte </td>
                            <td> Date Saisie </td>
                          </tfoot>
                        </table>
                      </div>
                    </div>
                  </form>
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
  <script src="js/panzoom.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="vendor/chart.js/Chart.min.js"></script>
  <script src="js/owner/set_side_bar.js"></script>
  <script src="js/owner/page_indicateur.js"></script>
  <script src="js/owner/count_lot.js"></script>
  <!-- next version -- 1.0.1   -->
  <script src="js/ajax/audit/audit_saisi.js?v=1.0.1"></script>

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