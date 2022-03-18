<?php 
  require_once "is_connect.php"; 

  $date_gen = date("Y-m-d");
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>  <?=$app_name ?> </title>
  <link rel="icon" href="img/favicon.ico" />

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <!-- <link href="vendor/mdbootstrap/css/mdb.min.css" rel="stylesheet"> -->
</head>

<body id="page-top">

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
          <div id="box-notif" style="display:none;position:absolute;right:20px;">
            <div class="bg-success d-flex justify-content-center"  
                style="border-radius:5px;color:white;padding:10px;box-shadow:1px 1px 10px rgba(0,0,0,0.3);">              
                <span class="badge badge-default text-success mr-2" style="padding:7px;background:white;border-radius:100%;"> 
                  <i class="fas fa-bell"></i> 
                </span> Nettoyage de la BD effectué
            </div>
          </div>
          <div class="row">
            <div class="text-center col-lg-12 mt-5"> 
              <div class="d-flex justify-content-center">
                <div style="background: black;padding: 45px;border-radius:100%; 
                    box-shadow: 5px 5px 20px rgba(0,0,0,0.5);" class=" mt-5">
                    <img src="./img/stats.png" alt="image home" class="img-responsive" style="height:150px;"/>
                </div>
              </div>

              <!-- <div class="mt-5" id="clean-btn-block">
                <button type="button" class="btn btn-dark" style="background: black;" id="btn-clean">
                  Nettoyer la base de données <span class="badge badge-danger"  style="font-size:15px;border-radius:100%;padding:5px;">  <i class="fas fa-quidditch"></i> </span>
                </button>
              </div>  -->
              
               <div id="block-form-clean" class="mt-5" style="display: none  ;">
                  <div class="row  d-flex justify-content-center">
                    <form>
                      <div class="form-row align-items-center">
                        <div class="col-auto">
                          <div class="input-group mb-2">
                            <div class="input-group-prepend">
                               <label class="input-group-text" for="dateDebut" style="background: black;color:white;"> Du <label>
                            </div>
                            <input type="date" class="form-control" id="dateDebut" placeholder="Username" value="<?=$date_gen?>">
                          </div>
                        </div>
                        <div class="col-auto">
                          <div class="input-group mb-2">
                            <div class="input-group-prepend">
                               <label class="input-group-text" for="dateFin" style="background: black;color:white;"> Au <label>
                            </div>
                            <input type="date" class="form-control" id="dateFin" placeholder="Username" value="<?=$date_gen?>">
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>

                  <div class="row form-inline  d-flex justify-content-center mt-2">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="checkActe" checked/>
                      <label class="form-check-label" for="checkActe">
                        Actes
                      </label>
                    </div>
                    <div class="form-check ml-3">
                      <input class="form-check-input" type="checkbox" id="checkMention" checked/>
                      <label class="form-check-label" for="checkMention">
                        Mentions
                      </label>
                    </div>
                    <div class="form-check ml-3">
                      <input class="form-check-input" type="checkbox" id="checkControle1" checked/>
                      <label class="form-check-label" for="checkControle1">
                        Conrôle 1
                      </label>
                    </div>
                    <div class="form-check ml-3">
                      <input class="form-check-input" type="checkbox" id="checkControle2" checked/>
                      <label class="form-check-label" for="checkControle2">
                        Contrôles 2
                      </label>
                    </div>
                  </div>

                  <div class="row form-inline  d-flex justify-content-center mt-4">
                    <a href="#" class="btn btn-dark mr-1" style="background: transparent;" id="btn-clean-cancel">
                      <i class="text-danger fas fa-times"></i>
                    </a>                    
                    <button class="btn btn-dark" style="background: black;" id="btn-clean-ok">
                      Nettoyer <span class="badge badge-danger"  style="font-size:15px;border-radius:100%;padding:5px;">  <i class="fas fa-quidditch"></i> </span>
                    </button>
                  </div>
              </div> 

               <div class="mt-5" style="display: none;" id="img-loader">
                <img src="./img/loader.gif" alt="loader"/>
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
  <script src="js/ajax/clean_db.js"></script>  

  <!-- modification du lien du download de fichier  -->
  <script>
      $(document).ready(function(){
            var linkExcel = $("#btnGenererExcel");
            var dateGen = $("#date_gen");
            
            dateGen.change(function(e) 
            {
              linkExcel.attr('href', linkExcel.attr('lienstatic') +  dateGen.val());
            });
      });
  </script>

</body>

</html>
