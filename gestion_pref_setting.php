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
              <span style="color:<?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;"> GESTION ECM <i class="fa fa-angle-double-right" aria-hidden="true"></i> </span> PREFERENCES
            </h4>
          </div>
          <hr />

          <!-- Content Row -->
          <div class="h6 mb-0 text-dark-800">
            <span style="color:<?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;"> <i class="fa fa-angle-double-right" aria-hidden="true"></i> </span> COLOR
          </div>
          <hr />
          <div class="row mt-3">
            <div class="col-lg-2 mb-4">
              <div class="card text-white shadow" style="background-color: #1f3f8f;">
                <div class="card-body">
                  Bleu Profond
                  <div class="text-white-50 small">Choisir ce thème</div>
                  <div class="text-white-50 small">
                    <a href="proccess/change_style_color.php?style=1f3f8f" class="btn btn-light">
                      <span class="icon text-light-6"><i class="fas fa-check-double"></i></span>
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-2 mb-4">
              <div class="card text-white shadow" style="background:#94282a;">
                <div class="card-body">
                  Rouge
                  <div class="text-white-50 small">Choisir ce thème</div>
                  <div class="text-white-50 small">
                    <a href="proccess/change_style_color.php?style=94282a" class="btn btn-light">
                      <span class="icon text-light-6"><i class="fas fa-check-double"></i></span>
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-2 mb-4">
              <div class="card text-white shadow" style="background:#408bd6;">
                <div class="card-body">
                  Bleu océan
                  <div class="text-white-50 small">Choisir ce thème</div>
                  <div class="text-white-50 small">
                    <a href="proccess/change_style_color.php?style=408bd6" class="btn btn-light">
                      <span class="icon text-light-6"><i class="fas fa-check-double"></i></span>
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-2 mb-4">
              <div class="card text-dark shadow" style="background: #3b2106;">
                <div class="card-body text-white">
                  Marron
                  <div class="text-white-50 small">Choisir ce thème</div>
                  <div class="text-white-50 small">
                    <a href="proccess/change_style_color.php?style=3b2106" class="btn btn-light">
                      <span class="icon text-light-6"><i class="fas fa-check-double"></i></span>
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-2 mb-4">
              <div class="card text-white shadow" style="background: #b35b29;">
                <div class="card-body">
                  Orange
                  <div class="text-white-50 small">Choisir ce thème</div>
                  <div class="text-white-50 small">
                    <a href="proccess/change_style_color.php?style=b35b29" class="btn btn-light">
                      <span class="icon text-light-6"><i class="fas fa-check-double"></i></span>
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-2 mb-4">
              <div class="card text-white" style="background: #000000;">
                <div class="card-body">
                  Noir
                  <div class="text-white-50 small">Choisir ce thème</div>
                  <div class="text-white-50 small">
                    <a href="proccess/change_style_color.php?style=000000" class="btn btn-light">
                      <span class="icon text-light-6"><i class="fas fa-check-double"></i></span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="h6 mb-0 text-dark-800 mt-5">
            <span style="color:<?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;"> <i class="fa fa-angle-double-right" aria-hidden="true"></i> </span> PARAMETRES INTERNES
          </div>
          <hr />
          <div class="row mt-3 pl-5 pt-3">
            <div class="form-inline">
              <label for="bd_extra" class="mr-3"> - BD INTERNE : </label>
              <input id="bd_extra" class="form-control" type="text" name="">
            </div>
          </div>
          <div class="row mt-3 pl-5 pt-3">
            <div class="form-inline">
              <label for="chemin_lot" class="mr-3"> - CHEMIN IM. : </label>
              <textarea rows="2" style="width:300px;" class="form-control" id="chemin_lot">

                  </textarea>
            </div>
          </div>
          <div class="row mt-3 pl-5 pt-3">
            <div class="form-inline">
              <label for="chemin_lot" class="mr-3"> - RACINE DEST. : </label>
              <input rows="2" style="width:300px;" class="form-control" id="destination_default" />
            </div>
          </div>
          <div class="row mt-3 pl-5 pt-3 mb-5">
            <div class="col-md-4 d-flex justify-content-end">
              <button id="btn-enregistrer" type="submit" class="btn btn-dark" style="background: transparent;color:black;border: 1px solid rgba(0,0,0,0.1);box-shadow: 1px 1px 5px rgba(0,0,0,0.2);">
                Enregistrer <span class="badge badge-success" style="font-size:15px;border-radius:100%;padding:5px;"> <i class="fas fa-check-double"></i> </span>
              </button>
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
  <script src="js/jquery.csv.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="vendor/chart.js/Chart.min.js"></script>
  <script src="js/owner/set_side_bar.js"></script>
  <script src="js/owner/page_indicateur.js"></script>

  <script>
    $(document).ready(function() {
      let preferencesData = null;
      // Chargement des données du fichier json
      (() => {
        $.get("proccess/ajax/preferences/get_preferences.php",
          function(data, status, jsxhr) {
            preferencesData = JSON.parse(data);
            console.log(preferencesData);
            $("#bd_extra").val(preferencesData.base_donnees_annex);
            let chemins = "";
            preferencesData.list_path_images.forEach((e) => {
              chemins += e + "\n";
            })
            $("#chemin_lot").val(chemins)
            $("#destination_default").val(preferencesData.destination_default)
            console.log(preferencesData);
          });

      })();

      $("#btn-enregistrer").on("click", function() {
        if ($("#bd_extra").val().trim() != "" && $("#chemin_lot").val().trim() != "" && $("#destination_default").val().trim() != "") {
          let chemins = $("#chemin_lot").val().trim().split("\n").filter(e => e.trim() != "");
          let NewData;
          if (preferencesData == null) {
            preferencesData = {
              base_donnees_annex: $("#bd_extra").val().trim(),
              list_path_images: chemins
            }
          } else {
            NewData = preferencesData;
            NewData.base_donnees_annex = $("#bd_extra").val().trim();
            NewData.list_path_images = chemins;
            NewData.destination_default = $("#destination_default").val().trim();
          }

          $.post('proccess/ajax/preferences/update_preferences.php', {
              data: JSON.stringify(NewData)
            },
            function(data, status, jqXHR) {
              console.log(data);
              alert("Paramètres Enregistrés !!")
            }
          );
        }
      });

    });
  </script>

</body>

</html>