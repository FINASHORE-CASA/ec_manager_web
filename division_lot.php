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

  <title>  <?=$app_name ?> </title>
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
            <h4 class="h6 mb-0 text-dark-800">
              <span style="color:<?=isset($main_app_color) ? $main_app_color : "#3b2106";?>;"> SAISIE <i class="fa fa-angle-double-right" aria-hidden="true"></i> </span>  DIVISION LOT 
            </h4>
          </div> 
          <hr />  

          <!-- Liste des onglets  -->
          <div>
            <nav class="nav nav-tabs">
              <a class="nav-tab-item nav-item nav-link active" href="#division" style="color: black;"> Division </a>
              <a class="nav-tab-item nav-item nav-link" href="#reconstitution" style="color: black;"> Réconstitution <span class="badge badge-dark ml-2" style="background:red;border-radius:100%;display: none;" id="notif-Resultat-bell"><i class="far fa-bell"></i></span></a>
            </nav>
          </div>  

          <!-- Modal Diviser Confirmation -->
          <div class="modal fade" id="ConfirmDiviserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header" style="background:<?=isset($main_app_color) ? $main_app_color : "#3b2106";?>;color:white;">
                  <h5 class="modal-title" id="exampleModalLabel"> Confirmer </h5>
                  <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <div class="modal-body"> Voulez vous vraiment diviser le(s) lot(s) ? </div>
                <div class="modal-footer">
                  <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                  <button id="btn-diviser-confirm" class="btn btn-danger" href="#"> Confirmer </button>
                </div>
              </div>
            </div>
          </div> 

          <!-- Modal Réconstituer Confirmation -->
          <div class="modal fade" id="ConfirmFusionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header" style="background:<?=isset($main_app_color) ? $main_app_color : "#3b2106";?>;color:white;">
                  <h5 class="modal-title" id="exampleModalLabel"> Confirmer </h5>
                  <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <div class="modal-body"> Voulez vous vraiment réconstituer le(s) lot(s) ? </div>
                <div class="modal-footer">
                  <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                  <button id="btn-fusion-confirm" class="btn btn-danger" href="#"> Confirmer </button>
                </div>
              </div>
            </div>
          </div>        
          
          <!-- Content Row -->
          <div class="tab-content">
            <div class="tab-pane active" id="division">  
              <div class="row">
                <div class="col-xl-8 mt-4 mb-4">
                  <div class="card shadow mb-4">
                    <form method="post" action="#">                      
                      <div class="card-header py-3" style="background:<?=isset($main_app_color) ? $main_app_color : "#3b2106";?>;">
                        <h6 class="m-0 font-weight-bold text-white"> Liste des lots à diviser (id_lot)</h6>
                      </div>
                      <div class="card-body">
                        <div id="form-idlot-field" class="row">
                            <div class="form-group col-md-4">
                                <div class="form-group">
                                  <textarea class="form-control" id="text-list-lot-diviser" rows="3" 
                                            style="align-content:center; overflow:auto;">
                                  </textarea>
                                </div>
                                <div class="form-group">
                                  <label for="select-choix-purge" style="color:black;"> Nombre de division (+lot) : </label>
                                  <select name="select-nb-division" id="select-nb-division" class="form-control">
                                    <option value="2"> 1 </option>
                                    <option value="2"> 2 </option>
                                    <option value="3" selected> 3 </option>
                                    <option value="4"> 4 </option>
                                    <option value="5"> 5 </option>
                                    <option value="6"> 6 </option>
                                    <option value="7"> 7 </option>
                                    <option value="8"> 8 </option>
                                    <option value="9"> 9 </option>
                                  </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                              <div class="d-flex justify-content-center mt-5" >
                                <div  id="txt-nb-lot-diviser" style="border: 1px solid gray;border-radius:100%;padding:35px;font-size:27px;">00</div>
                              </div>
                              <div class="d-flex justify-content-center mt-4">
                                <p id="txt-nb-lot-notif-diviser" text-std="Liste lot à diviser"> </p>
                              </div>
                            </div>
                        </div>
                        <div id="form-lot-loader-diviser" style="position: absolute;background:rgba(255, 255, 255,0.8);top:0;width:100%;left:0px;height:100%;display:none;z-index:10;">
                          <div class="d-flex justify-content-center" style="padding-top: 9em;">
                            <img src="./img/loader.gif" alt="loader wait" />
                          </div>
                          <div class="d-flex justify-content-center mt-3" style="color: black;">
                            <p> <b> Traitement en cours ... </b></p>
                          </div>
                        </div>
                      </div>
                      <div class="card-footer" id="form-idlot-footer">                              
                          <button class="btn btn-secondary" type="reset" id="btn-reset-diviser" data-dismiss="modal">Annuler</button>                      
                          <button type="submit" class="btn btn-dark" style="background: <?=isset($main_app_color) ? $main_app_color : "#3b2106";?>;" id="btn-diviser">
                            Diviser Lot <span class="badge badge-danger"  style="font-size:15px;border-radius:100%;padding:5px;"> <i class="fas fa-dice-d20"></i> </span>
                          </button>
                      </div>
                    </form>                  
                  </div>
                </div>
                <div class="col-xl-4 mt-4 mb-4">    
                  <div class="card shadow">          
                    <div class="card-header py-3" style="background:white;">                  
                      <h6 class="m-0 font-weight-bold" style="color:<?=isset($main_app_color) ? $main_app_color : "#3b2106";?>;"> Progression</h6>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <ul id="liste-indic-diviser" style="text-decoration: none;">
                          <li class="mb-2" style="display: none;"> Traitement Image Vide  <i class="fas fa-check text-success" style="margin-left:5px;font-size:20px;"></i> </li> 
                          <li class="mb-2" style="display: none;"> détails </li> 
                        </ul>
                      </div>
                    </div>
                    <div class="card-footer  bg-success"  style="display: none;" id="indic-termine-diviser">
                      <div class="d-flex justify-content-center" style="font-size: 15px;color:white;">
                        Terminé <i class="fas fa-check-double" style="margin-left:12px;margin-top:2px;"></i>
                      </div> 
                    </div>
                  </div>
                </div>
              </div>                
            </div> 

            <div class="tab-pane" id="reconstitution"> 
              <div class="row">
                <div class="col-xl-8 mt-4 mb-4">
                  <div class="card shadow mb-4">
                    <form method="post" action="#">                      
                      <div class="card-header py-3" style="background:<?=isset($main_app_color) ? $main_app_color : "#3b2106";?>;">
                        <h6 class="m-0 font-weight-bold text-white"> Liste des lots à reconstituer (id_lot)</h6>
                      </div>
                      <div class="card-body">
                        <div id="form-idlot-field" class="row">
                            <div class="form-group col-md-4">
                                  <div class="form-group">
                                    <textarea class="form-control" id="text-list-lot-fusion" rows="3" 
                                              style="align-content:center; overflow:auto;">
                                    </textarea>
                                  </div>
                            </div>
                            <div class="col-md-8">
                              <div class="d-flex justify-content-center mt-5" >
                                <div id="txt-nb-lot-fusion" style="border: 1px solid gray;border-radius:100%;padding:35px;font-size:27px;">00</div>
                              </div>
                              <div class="d-flex justify-content-center mt-4">
                                <p id="txt-nb-lot-notif-fusion" text-std="Liste lot à reconstituer"> </p>
                              </div>
                            </div>
                        </div>
                        <div id="form-lot-loader-fusion" style="position: absolute;background:rgba(255, 255, 255,0.8);top:0;width:100%;left:0px;height:100%;display:none;z-index:10;">
                          <div class="d-flex justify-content-center" style="padding-top: 9em;">
                            <img src="./img/loader.gif" alt="loader wait" />
                          </div>
                          <div class="d-flex justify-content-center mt-3" style="color: black;">
                            <p> <b> Traitement en cours ... </b></p>
                          </div>
                        </div>
                      </div>
                      <div class="card-footer" id="form-idlot-footer">                              
                          <button class="btn btn-secondary" type="reset" id="btn-reset-fusion" data-dismiss="modal">Annuler</button>                      
                          <button type="submit" class="btn btn-dark" style="background: <?=isset($main_app_color) ? $main_app_color : "#3b2106";?>;" id="btn-fusion">
                            réconstituer lot <span class="badge badge-primary"  style="font-size:15px;border-radius:100%;padding:5px;"><i class="fas fa-globe"></i> </span>
                          </button>
                      </div>
                    </form>                  
                  </div>
                </div>
                <div class="col-xl-4 mt-4 mb-4">    
                  <div class="card shadow">          
                    <div class="card-header py-3" style="background:white;">                  
                      <h6 class="m-0 font-weight-bold" style="color:<?=isset($main_app_color) ? $main_app_color : "#3b2106";?>;"> Progression</h6>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <ul id="liste-indic-fusion">
                          <li class="mb-2" style="display: none;"> Opération Terminé <i class="fas fa-check text-success" style="margin-left:5px;font-size:20px;"></i> </li> 
                          <li class="mb-2" style="display: none;"> Détails </li>
                        </ul>
                      </div>
                    </div>
                    <div class="card-footer  bg-success"  style="display: none;" id="indic-termine-fusion">
                      <div class="d-flex justify-content-center" style="font-size: 15px;color:white;">
                        Terminé <i class="fas fa-check-double" style="margin-left:12px;margin-top:2px;"></i>
                      </div> 
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

  <!-- Page level plugins -->  
  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="js/demo/datatables-demo.js"></script>  
  <!-- <script src="vendor/click-tap-image/dist/js/image-zoom.min.js"></script> -->

  <!-- Page level custom scripts -->
  <script src="vendor/chart.js/Chart.min.js"></script>
  <script src="js/owner/set_side_bar.js"></script>
  <script src="js/owner/page_indicateur.js"></script>
  <script src="js/owner/count_lot_division_lot.js"></script>
  <script src="js/ajax/saisi/saisi_division_lot.js"></script>
  <script src="js/ajax/saisi/saisi_reconstruct_lot.js"></script>

  <script>
    $(document).ready(function(e)
    {     
      $('.nav-tab-item').click(function (e) 
          {        
            e.preventDefault()
            $(this).tab('show')
          }).on('shown.bs.tab', function (e) 
            {
              $('#actif').text($(e.target).text())
              $('#precedent').text($(e.relatedTarget).text())
            })
    });
  </script>

</body>

</html>
