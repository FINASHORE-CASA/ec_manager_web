<?php
require_once "is_connect.php";
require_once "./config/checkConfig.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title> <?= $app_name ?> </title>
  <link rel="icon" href="img/favicon.ico" />

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <!-- <link href="vendor/mdbootstrap/css/mdb.min.css" rel="stylesheet"> -->
</head>

<body id="page-top" idpage="StatsPage">

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
              <span style="color:<?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                STATS <i class="fa fa-angle-double-right" aria-hidden="true"></i>
              </span>
            </h4>
          </div>
          <hr />

          <div class="row">
            <div class="col-lg-4 mb-4">
              <div class="card text-white shadow">
                <div class="card-body text-dark">
                  <span style="color:<?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;"> Lot Finalisés </span>
                  <hr />
                  <div class="text-dark-50" style="font-size: 72px;" id="nb_lot_a">

                  </div>
                  <img src="./img/loader.gif" alt="loader wait" style="height: 40px;width:40px;" class="loader" />
                  <div class="text-white-50 small">
                    <a id="btn_show_details_lot_A" href="#" class="btn btn-light" style="display: none;">
                      <span class="icon text-light-6"> <i class="fa fa-plus" aria-hidden="true"></i></span>
                    </a>
                  </div>
                  <hr style="background-color: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;" />
                  <div class="float-right">
                    <textarea id="details_lot_A" readonly="true" style="overflow-y: auto;height:300px;padding-right:20px;box-shadow:1px 0px 5px rgba(0,0,0,0.1) inset;
                                  margin-left:50px;background:<?= isset($main_app_color) ? $main_app_color . "0A" : "#3b210633"; ?>;
                                  display:none;" class="text-right" value="">
                    </textarea>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-4 mb-4">
              <div class="card text-white shadow">
                <div class="card-body text-dark">
                  <span style="color:<?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                    Lot Non Finalisés
                  </span>
                  <hr />
                  <div class="text-dark-50" style="font-size: 72px;" id="nb_lot_non_a">

                  </div>
                  <img src="./img/loader.gif" alt="loader wait" style="height: 40px;width:40px;" class="loader" />
                  <div class="text-white-50 small">
                    <a id="btn_show_details_lot_non_A" href="#" class="btn btn-light" style="display: none;">
                      <span class="icon text-light-6"> <i class="fa fa-plus" aria-hidden="true"></i> </span>
                    </a>
                  </div>
                  <hr style="background-color: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;" />
                  <div class="float-right">
                    <textarea id="details_lot_non_A" readonly="true" style="overflow-y: auto;height:300px;padding-right:20px;box-shadow:1px 0px 5px rgba(0,0,0,0.1) inset;
                                      margin-left:50px;background:<?= isset($main_app_color) ? $main_app_color . "0A" : "#3b21060A"; ?>;
                                      display:none;" class="text-right" value="">
                    </textarea>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-4 mb-4">
              <div class="card text-white shadow">
                <div class="card-body text-dark">
                  <span style="color:<?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                    Nombre Acte Finalisé
                  </span>
                  <hr />
                  <img src="./img/loader.gif" alt="loader wait" style="height: 40px;width:40px;" class="loader" />
                  <div class="text-dark-50" style="font-size: 22px;" id="nb_acte_lot">

                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- End of Main Content -->

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
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="js/owner/set_side_bar.js"></script>
    <script src="js/owner/page_indicateur.js"></script>

    <!-- modification du lien du download de fichier  -->
    <script>
      $(document).ready(function() {
        var linkExcel = $("#btnGenererExcel");
        var dateGen = $("#date_gen");

        dateGen.change(function(e) {
          linkExcel.attr('href', linkExcel.attr('lienstatic') + dateGen.val());
        });
      });
    </script>

    <script>
      $(document).ready(function() {
        function geStats() {

          console.log("lancement ...");
          $.get('./proccess/ajax/stats/get_stats.php')
            .done(function(data) {
              try {
                console.log(data);
                var result = JSON.parse(data);

                // Dissimulation des loader
                $(".loader").each(function() {
                  $(this).css("display", "none");
                });

                // Affichage des boutons +
                $("#btn_show_details_lot_A").css("display", "inline");
                $("#btn_show_details_lot_non_A").css("display", "inline");

                $("#nb_acte_lot").text(result[3] + " / " + result[4]);
                $("#nb_lot_a").html(result[1].length);
                $("#nb_lot_non_a").html(result[2].length);

                let htmlDivLotA = "";
                result[1].forEach(function(e) {
                  htmlDivLotA += e.id_lot + '\n';
                });
                $("#details_lot_A").html(htmlDivLotA);

                let htmlDivLotNonA = "";
                result[2].forEach(function(e) {
                  htmlDivLotNonA += e.id_lot + '\n';
                });
                $("#details_lot_non_A").html(htmlDivLotNonA);

                // injection des données                             
                htmlDataDb = "";
              } catch (error) {
                console.log(error);
              }
            })
            .fail(function(fails, error) {
              console.log(fails);
              console.log(error);
            });
        }
        geStats();

        $("#btn_show_details_lot_A").on("click", function(e) {
          if ($("#details_lot_A").css("display") == 'none') {
            $("#details_lot_A").css("display", "inherit");
            $("#btn_show_details_lot_A").html("<span class='icon text-light-6'> <i class='fa fa-minus' aria-hidden='true'></i></span>");
          } else {
            $("#details_lot_A").css("display", "none");
            $("#btn_show_details_lot_A").html("<span class='icon text-light-6'> <i class='fa fa-plus' aria-hidden='true'></i></span>");
          }
          e.preventDefault();
        });

        $("#btn_show_details_lot_non_A").on("click", function(e) {
          if ($("#details_lot_non_A").css("display") == 'none') {
            $("#details_lot_non_A").css("display", "inherit");
            $("#btn_show_details_lot_non_A").html("<span class='icon text-light-6'> <i class='fa fa-minus' aria-hidden='true'></i></span>");
          } else {
            $("#details_lot_non_A").css("display", "none");
            $("#btn_show_details_lot_non_A").html("<span class='icon text-light-6'> <i class='fa fa-plus' aria-hidden='true'></i></span>");
          }
          e.preventDefault();
        });
      });
    </script>

</body>

</html>