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

  <title> <?=$app_name ?> </title>
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

<body id="page-top" idpage="Livraison">

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
              <span style="color:<?=isset($main_app_color) ? $main_app_color : "#3b2106";?>;"> 
                LIVRAISON <i class="fa fa-angle-double-right" aria-hidden="true"></i> 
              </span>  TRANSFERT LOT 
            </h4>
          </div>        
          <hr />

          <!-- Modal Text Livraison final -->
          <div class="modal fade" id="textLivreModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header" style="background:black;color:white;">
                  <h5 class="modal-title" id="exampleModalLabel"> Liste Lot Livré </h5>
                  <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <div class="modal-body"> 
                  <label for="text-list-lot-livre"> id_lot ( <span id="list-notif-idlot-livre" class="text-danger"> 100 </span> ) </label>               
                  <textarea class="form-control" id="text-list-lot-livre" rows="10" 
                            style="align-content:center; overflow:auto;">
                  </textarea>
                </div>
                <div class="modal-footer">
                  <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                  <button id="btn-purge-livre-confirm" class="btn btn-darker" style="background: black;color:white;" href="#"> Enregistrer </button>
                </div>
              </div>
            </div>
          </div>  
          
          <!-- Content Row -->
          <div class="row">            
            <div class="col-xl-8 mt-4 mb-4">
              <div class="card shadow mb-4">
                <form method="post" action="#">                      
                  <div class="card-header py-3" style="background:<?=isset($main_app_color) ? $main_app_color : "#3b2106";?>;">
                    <h6 class="m-0 font-weight-bold text-white"> Liste des lots à transférer (id_lot)</h6>
                  </div>
                  <div class="card-body">
                    <div id="form-idlot-field" class="row">
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
                            <p id="txt-nb-lot-notif" text-std="Nombre de lot à transferer"> </p>
                          </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">                      
                      <div class="form-group ml-3">
                        <label for="select-choix-purge" style="color:black;"> Source : </label>
                        <div class="form-inline">
                          <textarea class="form-control" id="text-list-source" rows="1"   
                                    style="align-content:center; overflow:auto;height:40px;max-height:70px;min-height:40px;">
                          </textarea>
                          <a id="btn-save-source" class="btn btn-dark ml-1" style="color:white;background-color: <?=isset($main_app_color) ? $main_app_color : "#3b2106";?>;vertical-align:top;"> <i class="far fa-save" style="font-size: 14px;"></i> </a>                      
                        </div>
                      </div>       
                      <div class="ml-5 mr-5 d-flex justify-content-center mt-5">
                        <i class="fas fa-exchange-alt"></i>
                      </div>              
                      <div class="form-group ml-3">
                        <label for="select-choix-purge" style="color:black;"> Destination : </label>
                        <div class="form-inline">
                          <input class="form-control" id="text-destination"  
                                style="align-content:center; overflow:auto;height:40px;max-height:70px;min-height:40px;"/>
                          <a id="btn-save-destination" class="btn btn-dark ml-1" style="color:white;background-color: <?=isset($main_app_color) ? $main_app_color : "#3b2106";?>;vertical-align:top;"> <i class="far fa-save" style="font-size: 14px;"></i> </a>                      
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
                      <button type="button" class="btn btn-dark" style="background: <?=isset($main_app_color) ? $main_app_color : "#3b2106";?>;" id="btn-transfert">
                        lancer le transfert <span class="badge badge-success"  style="font-size:15px;border-radius:100%;padding:5px;
                        color: <?=isset($main_app_color) ? $main_app_color : "#3b2106";?>;background:white;"> 
                          <i class="fas fa-exchange-alt"></i>  
                        </span>
                      </button>
                  </div>
                </form>                  
              </div>
            </div>
            <div class="col-xl-4 mt-4 mb-4">    
              <div class="card shadow">          
                <div class="card-header py-3" style="background:white;">                  
                  <h6 class="m-0 font-weight-bold" style="color:<?=isset($main_app_color) ? $main_app_color : "#3b2106";?>"> Progression </h6>
                </div>
                <div class="card-body">
                  <div class="row">
                    <ul id="liste-indic" class="list-unstyled" style="width: 97%;display:none;">
                      <li> 
                        <span id="indic_text"> (0/100) </span>
                        <div id="indic_progress" class="progress" style="height: 10px;">
                          <div class="progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                      </li>                       
                    </ul>
                  </div>
                </div>
                <div class="card-footer  bg-success"  style="display: none;" id="indic-termine">
                  <div class="d-flex justify-content-center" style="font-size: 15px;color:white;">
                    Terminé <i class="fas fa-check-double" style="margin-left:12px;margin-top:2px;"></i>
                  </div> 
                </div>
              </div>

              <div id="lot_non_transfere" class="card shadow mt-2" style="display:none;">          
                <div class="card-header py-3 bg-danger">                  
                  <h6 class="m-0 font-weight-bold text-white" > non transférés </h6>
                </div>
                <div class="card-body" id="lot_non_trans">
                  
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
  <script src="js/owner/count_lot.js"></script>
  <script src="js/ajax/livraison/livraison_transfert_lot.js"></script>

</body>

</html>
