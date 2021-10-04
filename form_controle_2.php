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

  <title>FINA - STATS</title>
  <link rel="icon" href="img/favicon.ico" />

  <!-- Custom fonts for this template -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <link rel="icon" href="img/favicon.ico" />
  
  <!-- Custom styles for this template -->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top" idpage="etatControle2">

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
            <h1 class="h3 mb-0 text-white-800 ">
                CONTROLE 2
            </h1>
            <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
          </div>          
          
          <!-- Content Row -->
          <div class="row">            
            <div class="col-xl-12 col-md-12 mt-4 mb-4">
              <div class="card shadow mb-4">
                <form method="post" action="./proccess/xlsx_generator/genener_controle_2.php">                      
                  <div class="card-header py-3"  style="background:black;">
                    <h6 class="m-0 font-weight-bold text-white"> Génerer le Nombre contrôle 2 </h6>
                  </div>
                  <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="exampleFormControlSelect1"> Du </label>
                                <div class="input-group col-md-12 col-lg-12">
                                      <div class="input-group-prepend">
                                          <button type="button" class="btn btn-outline-secondary" style="z-index:inherit">Du : </button>
                                          </button>
                                      </div>
                                      <input type="date" class="form-control" id="date_gen_deb" name="date_gen_deb"
                                            value="<?=$date_gen?>"
                                            aria-label="Text input with segmented dropdown button" required/>
                                </div>
                          </div>
                          <div class="form-group col-md-4">
                            <label for="exampleFormControlSelect1"> Au </label>
                                <div class="input-group col-md-12 col-lg-12">
                                      <div class="input-group-prepend">
                                          <button type="button" class="btn btn-outline-secondary" style="z-index:inherit">Au : </button>
                                          </button>
                                      </div>
                                      <input type="date" class="form-control" id="date_gen_fin" name="date_gen_fin"
                                            value="<?=$date_gen?>"
                                            aria-label="Text input with segmented dropdown button"/ required>
                                </div>
                          </div>
                    </div>
                  </div>
                  <div class="card-footer">                              
                      <button class="btn btn-secondary" type="reset" data-dismiss="modal">Annuler</button>                      
                      <button class="btn btn-dark" style="background: black;"  type="submit">
                        Generer le XLSX  <span class="badge badge-success"  style="font-size:12px;border-radius:100%;padding:10px;">  <i class="fas fa-download"></i> </span>
                      </button>
                  </div>
                </form>                  
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

  <!-- Page level custom scripts -->
  <script src="js/demo/datatables-demo.js"></script>  
  <script src="js/owner/page_indicateur.js"></script>
  <script src="vendor/chart.js/Chart.min.js"></script>
  <script src="js/owner/set_side_bar.js"></script>


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
