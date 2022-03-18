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

<body id="page-top" idpage="Saisie">

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
            <h1 class="h3 mb-0 text-dark-800">
              <span class="badge badge-dark" style="background: black;"> 1 ° </span> <span style="font-size:22px;"> CONTROLE ACTE </span>
            </h1>
          </div>        
          <hr />  
          
          <!-- Content Row -->
          <div class="row">            
            <div class="col-xl-8 mt-4 mb-4">
              <div class="card shadow mb-4">
                <form method="post" action="#">                      
                  <div class="card-header py-3" style="background:black;">
                    <h6 class="m-0 font-weight-bold text-white"> Liste des lots à traiter (id_lot)</h6>
                  </div>
                  <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-4">
                              <div class="form-group">
                                <textarea class="form-control" id="text-list-lot" rows="9" 
                                          style="align-content:center; overflow:auto;">
                                </textarea>
                              </div>
                        </div>
                        <div class="col-md-8">
                          <div class="d-flex justify-content-center mt-5" >
                            <div  id="txt-nb-lot" style="border: 1px solid gray;border-radius:100%;padding:35px;font-size:27px;">00</div>
                          </div>
                          <div class="d-flex justify-content-center mt-4">
                            <p id="txt-controle-notif"> Le Contrôle sera effectué sur ces lots </p>
                          </div>
                        </div>
                    </div>
                  </div>
                  <div class="card-footer">                              
                      <button class="btn btn-secondary" type="reset" data-dismiss="modal">Annuler</button>                      
                      <button type="submit" class="btn btn-dark" style="background: black;" id="btn-controle">
                        Lancer le Contrôle <span class="badge badge-success"  style="font-size:15px;border-radius:100%;padding:5px;"><i class="fas fa-check-double"></i> </span>
                      </button>
                  </div>
                </form>                  
              </div>
            </div>
            <div class="col-xl-4 mt-4 mb-4">    
              <div class="card shadow">          
                <div class="card-header py-3" style="background:black;">
                  <h6 class="m-0 font-weight-bold text-white"> Progression </h6>
                </div>
                <div class="card-body">
                  <div class="row">
                    <ol>
                      <li id="indic-img-vide" class="mb-2"> Traitement Image Vide </li> 
                      <li id="indic-num-acte-vide" class="mb-2"> Traitement Numéro Acte Vide </li>
                      <li id="indic-num-acte-diff-img" class="mb-2"> Traitement Num Acte # imagepath </li>
                      <li id="indic-saisi-double" class="mb-2"> Traitement Image saisit en double </li>
                      <li id="indic-num-acte-double" class="mb-2"> Traitement Num_Acte en double</li>
                    </ol>
                  </div>
                </div>
                <div class="card-footer  bg-success">
                  <div class="d-flex justify-content-center" style="font-size: 15px;color:white;">
                    Terminé <i class="fas fa-check-double" style="margin-left:12px;margin-top:2px;"></i>
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

  <!-- Page level custom scripts -->
  <script src="js/demo/datatables-demo.js"></script>  
  <script src="vendor/chart.js/Chart.min.js"></script>
  <script src="js/owner/set_side_bar.js"></script>
  <script src="js/owner/page_indicateur.js"></script>
  <script src="js/owner/saisi_count_lot.js"></script>
  <script src="js/ajax/saisi_controle_perfom.js"></script>

</body>

</html>
