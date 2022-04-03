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
              <span style="color:<?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;"> GESTION STATS <i class="fa fa-angle-double-right" aria-hidden="true"></i> </span> STATS GENERAL
            </h4>
          </div>
          <hr />

          <!-- Liste des onglets  -->
          <div>
            <nav class="nav nav-tabs">
              <a class="nav-tab-item nav-item nav-link active" href="#ActeSaisi" style="color: black;">
                Acte Saisie
              </a>
              <a class="nav-tab-item nav-item nav-link" href="#Mention" style="color: black;">
                Mention
              </a>
              <a class="nav-tab-item nav-item nav-link" href="#Controle1" style="color: black;">
                Contrôle 1
              </a>
              <a class="nav-tab-item nav-item nav-link" href="#Controle2" style="color: black;">
                Contrôle 2
              </a>
            </nav>
          </div>

          <!-- lien de téléchargement -->
          <a id="download" href="#" type="download" style="display:none;"> télécharger </a>

          <!-- Content Row -->
          <div class="tab-content">
            <div class="tab-pane active" id="ActeSaisi">
              <div class="col-xl-12 mt-4 mb-4">
                <div class="card shadow mb-4">
                  <form method="post" action="./proccess/xlsx_generator/genener_acte_saisi.php">
                    <div class="card-header py-3" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                      <h6 class="m-0 font-weight-bold text-white"> Génerer le Nombre d'actes saisies par Agents </h6>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="form-group col-md-4">
                          <label for="exampleFormControlSelect1"> Du </label>
                          <div class="input-group col-md-12 col-lg-12">
                            <div class="input-group-prepend">
                              <button type="button" class="btn btn-outline-secondary" style="z-index:inherit">Du : </button>
                            </div>
                            <input type="date" class="form-control date_debut" id="date_gen_deb_acte" name="date_gen_deb" value="<?= $date_gen ?>" aria-label="Text input with segmented dropdown button" required />
                          </div>
                        </div>
                        <div class="form-group col-md-4">
                          <label for="exampleFormControlSelect1"> Au </label>
                          <div class="input-group col-md-12 col-lg-12">
                            <div class="input-group-prepend">
                              <button type="button" class="btn btn-outline-secondary" style="z-index:inherit">Au : </button>
                            </div>
                            <input type="date" class="form-control date_fin" id="date_gen_fin_acte" name="date_gen_fin" value="<?= $date_gen ?>" aria-label="Text input with segmented dropdown button" required />
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-footer">
                      <button class="btn btn-secondary" type="reset" data-dismiss="modal">Annuler</button>
                      <button type="submit" class="btn btn-dark" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                        Generer le XLSX <span class="badge badge-success" style="font-size:12px;border-radius:100%;padding:10px;"> <i class="fas fa-download"></i> </span>
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <div class="tab-pane" id="Mention">
              <div class="col-xl-12 mt-4 mb-4">
                <div class="card shadow mb-4">
                  <form method="post" action="./proccess/xlsx_generator/genener_calcul_mention.php">
                    <div class="card-header py-3" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                      <h6 class="m-0 font-weight-bold text-white"> Génerer le Calcul de la Mention </h6>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="form-group col-md-4">
                          <label for="exampleFormControlSelect1"> Du </label>
                          <div class="input-group col-md-12 col-lg-12">
                            <div class="input-group-prepend">
                              <button type="button" class="btn btn-outline-secondary" style="z-index:inherit">Du : </button>
                            </div>
                            <input type="date" class="form-control date_debut" id="date_gen_deb_mention" name="date_gen_deb" value="<?= $date_gen ?>" aria-label="Text input with segmented dropdown button" required />
                          </div>
                        </div>
                        <div class="form-group col-md-4">
                          <label for="exampleFormControlSelect1"> Au </label>
                          <div class="input-group col-md-12 col-lg-12">
                            <div class="input-group-prepend">
                              <button type="button" class="btn btn-outline-secondary" style="z-index:inherit">Au : </button>
                            </div>
                            <input type="date" class="form-control date_fin" id="date_gen_fin_mention" name="date_gen_fin" value="<?= $date_gen ?>" aria-label="Text input with segmented dropdown button" required />
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-footer">
                      <button class="btn btn-secondary" type="reset" data-dismiss="modal">Annuler</button>
                      <button class="btn btn-dark" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;" type="submit">
                        Generer le XLSX <span class="badge badge-success" style="font-size:12px;border-radius:100%;padding:10px;"> <i class="fas fa-download"></i> </span>
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <div class="tab-pane" id="Controle1">
              <div class="col-xl-12 mt-4 mb-4">
                <div class="card shadow mb-4">
                  <form method="post" action="./proccess/xlsx_generator/genener_controle_1.php">
                    <div class="card-header py-3" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                      <h6 class="m-0 font-weight-bold text-white"> Génerer le Nombre Contrôle 1 </h6>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="form-group col-md-4">
                          <label for="exampleFormControlSelect1"> Du </label>
                          <div class="input-group col-md-12 col-lg-12">
                            <div class="input-group-prepend">
                              <button type="button" class="btn btn-outline-secondary" style="z-index:inherit">Du : </button>
                            </div>
                            <input type="date" class="form-control date_debut" id="date_gen_deb_controle1" name="date_gen_deb" value="<?= $date_gen ?>" aria-label="Text input with segmented dropdown button" required />
                          </div>
                        </div>
                        <div class="form-group col-md-4">
                          <label for="exampleFormControlSelect1"> Au </label>
                          <div class="input-group col-md-12 col-lg-12">
                            <div class="input-group-prepend">
                              <button type="button" class="btn btn-outline-secondary" style="z-index:inherit">Au : </button>
                            </div>
                            <input type="date" class="form-control date_fin" id="date_gen_fin_controle1" name="date_gen_fin" value="<?= $date_gen ?>" aria-label="Text input with segmented dropdown button" required />
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-footer">
                      <button class="btn btn-secondary" type="reset" data-dismiss="modal">Annuler</button>
                      <button class="btn btn-dark" type="submit" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                        Generer le XLSX <span class="badge badge-success" style="font-size:12px;border-radius:100%;padding:10px;"> <i class="fas fa-download"></i> </span>
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <div class="tab-pane" id="Controle2">
              <div class="col-xl-12 mt-4 mb-4">
                <div class="card shadow mb-4">
                  <form method="post" action="./proccess/xlsx_generator/genener_controle_2.php">
                    <div class="card-header py-3" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                      <h6 class="m-0 font-weight-bold text-white"> Génerer le Nombre Contrôle 2 </h6>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="form-group col-md-4">
                          <label for="exampleFormControlSelect1"> Du </label>
                          <div class="input-group col-md-12 col-lg-12">
                            <div class="input-group-prepend">
                              <button type="button" class="btn btn-outline-secondary" style="z-index:inherit">Du : </button>
                            </div>
                            <input type="date" class="form-control date_debut" id="date_gen_deb_controle2" name="date_gen_deb" value="<?= $date_gen ?>" aria-label="Text input with segmented dropdown button" required />
                          </div>
                        </div>
                        <div class="form-group col-md-4">
                          <label for="exampleFormControlSelect1"> Au </label>
                          <div class="input-group col-md-12 col-lg-12">
                            <div class="input-group-prepend">
                              <button type="button" class="btn btn-outline-secondary" style="z-index:inherit">Au : </button>
                            </div>
                            <input type="date" class="form-control date_fin" id="date_gen_fin_controle2" name="date_gen_fin" value="<?= $date_gen ?>" aria-label="Text input with segmented dropdown button" required />
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-footer">
                      <button class="btn btn-secondary" type="reset" data-dismiss="modal">Annuler</button>
                      <button class="btn btn-dark" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;" type="submit">
                        Generer le XLSX <span class="badge badge-success" style="font-size:12px;border-radius:100%;padding:10px;"> <i class="fas fa-download"></i> </span>
                      </button>
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

  <script>
    $(document).ready(function() {

      $(".date_debut").on("change", function() {
        let date_debut = $(this).val()
        $(".date_debut").each(function() {
          $(this).val(date_debut)
        });
      });

      $(".date_fin").on("change", function() {
        let date_fin = $(this).val()
        $(".date_fin").each(function() {
          $(this).val(date_fin)
        });
      });

    });
  </script>

</body>

</html>