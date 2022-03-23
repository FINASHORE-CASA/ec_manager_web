<?php
require_once "is_connect.php";
require_once "./config/checkConfig.php";


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

        <!-- Modal -->
        <div class="modal fade" id="AgentAffectationAudit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                <h5 class="modal-title" id="AgentAuditLogin" style="color: white;"> <i class="far fa-user-circle"></i> Agent 1 </h5>
                <div style="position:absolute;right:5px;">
                </div>
              </div>
              <div class="modal-body">
                <div id="formAffectAgentAudit" class="row">
                  <form class="mt-2" style="width: 100%;">
                    <input type="hidden" id="field-Id_user" value="<?= isset($_SESSION['user']) ? $_SESSION['user']->id_user : '' ?>" />
                    <div class="row m-3">
                      <div class="col-md-12">
                        <div id="list-lots-user">
                          <span class="badge badge-dark mt-1"> 11989002143701 | <a href="#" id_supp_lot_aff="0"> <span class="fas fa-times-circle text-danger"> </span> </a> </span>
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

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <h4 class="h6 mb-0 text-dark-800">
              <span style="color:<?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;"> AUDIT <i class="fa fa-angle-double-right" aria-hidden="true"></i> </span> LOT DISPONIBLE
            </h4>
          </div>
          <hr />

          <!-- Liste des onglets  -->
          <div>
            <nav class="nav nav-tabs">
              <a class="nav-tab-item nav-item nav-link active" href="#AuditSaisi" style="color: black;"> Audit Saisie </a>
              <a class="nav-tab-item nav-item nav-link" href="#AuditControle1" style="color: black;"> Audit Contrôle 1 </a>
              <a class="nav-tab-item nav-item nav-link" href="#AuditControle2" style="color: black;"> Audit Contrôle 2 </a>
            </nav>
          </div>


          <!-- lien de téléchargement -->
          <a id="download" href="#" type="download" style="display:none;"> télécharger </a>

          <!-- Content Row -->
          <div class="tab-content">
            <div class="tab-pane active" id="AuditSaisi">
              <div class="row">
                <div class="col-xl-3 mt-4 mb-4">
                  <div class="card shadow mb-4">
                    <div class="card-header py-3" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                      <h6 class="m-0 font-weight-bold text-white"> Gestion Agent </h6>
                    </div>
                    <div class="card-body">
                      <div class="mb-4">
                        <input class="form-control" type="text" placeholder="rechercher" />
                      </div>
                      <div class="table-responsive">
                        <table class="table" id="dataTableAgentAuditSaisi" width="100%" cellspacing="0">
                          <tbody id="TableAgentAuditSaisi">
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-9 mt-4 mb-4">
                  <div class="card shadow mb-4">
                    <form method="post" action="#">
                      <div class="card-header py-3" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                        <h6 class="m-0 font-weight-bold text-white"> Liste des lots disponibles (Audit Saisie) <button id="audit_saisie_dl" class="text-white float-right" style="background-color: transparent;border:none;"> <i class="fa fa-download" aria-hidden="true"></i> </button> </h6>
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

            <div class="tab-pane" id="AuditControle1">
              <div class="row">
                <div class="col-xl-12 mt-4 mb-4">
                  <div class="card shadow mb-4">
                    <form method="post" action="#">
                      <div class="card-header py-3" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                        <h6 class="m-0 font-weight-bold text-white"> Liste des lots disponibles (Audit Contrôle 1) <button id="audit_controle1_dl" class="text-white float-right" style="background-color: transparent;border:none;"> <i class="fa fa-download" aria-hidden="true"></i> </button> </h6>
                      </div>
                      <div class="card-body">
                        <div class="table-responsive">
                          <table class="table table-bordered" id="dataTableAuditControle1" width="100%" cellspacing="0">
                            <thead>
                              <tr>
                                <th> Id lot </th>
                                <th> Commune </th>
                                <th> Bureau </th>
                                <th> Nombre acte </th>
                                <th> Date Saisie </th>
                              </tr>
                            </thead>
                            <tbody id="TableAuditControle1">
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

            <div class="tab-pane" id="AuditControle2">
              <div class="row">
                <div class="col-xl-12 mt-4 mb-4">
                  <div class="card shadow mb-4">
                    <form method="post" action="#">
                      <div class="card-header py-3" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                        <h6 class="m-0 font-weight-bold text-white"> Liste des lots disponibles (Audit Contrôle 2) <button id="audit_controle2_dl" class="text-white float-right" style="background-color: transparent;border:none;"> <i class="fa fa-download" aria-hidden="true"></i> </button> </h6>
                      </div>
                      <div class="card-body">
                        <div class="table-responsive">
                          <table class="table table-bordered" id="dataTableAuditControle2" width="100%" cellspacing="0">
                            <thead>
                              <tr>
                                <th> Id lot </th>
                                <th> Commune </th>
                                <th> Bureau </th>
                                <th> Nombre acte </th>
                                <th> Date Saisie </th>
                              </tr>
                            </thead>
                            <tbody id="TableAuditControle2">
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
  <script src="js/ajax/audit/audit_lot_dispo.js?version=1.0.3"></script>

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