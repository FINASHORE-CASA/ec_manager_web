<?php
require_once "is_connect.php";
require_once "./config/checkConfig.php";

$date_gen = date("Y-m-d");

// Récupération des id_lots concernés        
$qry = $bdd->prepare("SELECT id_lot FROM lot where status_lot = 'A'");
$qry->execute();
$id_lots = $qry->fetchAll(PDO::FETCH_OBJ);
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

        <!-- Modal -->
        <div class="modal fade" id="ActeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
          <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
              <div class="modal-header" style="background: black;">
                <h5 class="modal-title" id="exampleModalLabel" style="color: white;"> Modification Acte </h5>
                <button type="button" class="close btn-form-modal-cancel" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-6" style="height:700px;overflow:auto;">
                    <form class="mt-2">
                      <h6 style="color: black;"> Formulaire Acte </h6>
                      <hr />
                      <div class="row">
                        <div class="form-group col-md-6">
                          <label for="field-IdLot">Id Lot</label>
                          <input type="text" class="form-control" id="field-IdLot" aria-describedby="field-IdLot" placeholder="" disabled />
                        </div>
                        <div class="form-group col-md-6">
                          <label for="field-IdActe">Id Acte</label>
                          <input type="text" class="form-control" id="field-IdActe" aria-describedby="field-IdActe" placeholder="" disabled />
                        </div>
                      </div>
                      <hr />
                      <div class="row">
                        <div class="form-group col-md-6">
                          <label for="field-NumActe">Num Acte</label>
                          <input type="text" class="form-control" id="field-NumActe" aria-describedby="field-NumActe" placeholder="">
                        </div>
                        <div class="form-group col-md-6">
                          <label for="field-NomFr">Imagepath</label>
                          <input type="text" class="form-control" id="field-NomFr" aria-describedby="field-Imagepath" placeholder="" disabled />
                        </div>
                      </div>
                      <hr />
                      <div class="row">
                        <div class="form-group col-md-6">
                          <label for="field-NomFr">Nom fr</label>
                          <input type="text" class="form-control" id="field-NomFr" aria-describedby="field-NomFr" placeholder="" disabled />
                        </div>
                        <div class="form-group col-md-6">
                          <label for="field-PrenomFr">Prenom fr</label>
                          <input type="text" class="form-control" id="field-PrenomFr" aria-describedby="field-PrenomFr" placeholder="" disabled />
                        </div>
                      </div>
                      <hr />
                      <div class="row">
                        <div class="form-group col-md-6">
                          <label for="field-NomAr">Nom ar</label>
                          <input type="text" class="form-control" id="field-NomAr" aria-describedby="field-NomAr" placeholder="" disabled />
                        </div>
                        <div class="form-group col-md-6">
                          <label for="field-PrenomAr">Prenom ar</label>
                          <input type="text" class="form-control" id="field-PrenomAr" aria-describedby="field-PrenomAr" placeholder="" disabled />
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="col-md-6">
                    <div id="img-block" style="min-height: 700px;">
                    </div>
                    <div style="height:50px;" class="text-center mt-3" id="block-img-change">
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-form-modal-cancel" data-dismiss="modal"> Annuler</button>
                <button id="form-update-save" type="button" class="btn btn-primary" style="background: black;"> Enregistrer <i class="far fa-save ml-1"></i></button>
              </div>
            </div>
          </div>
        </div>

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <h4 class="h6 mb-0 text-dark-800">
              <span style="color:<?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;"> LIVRAISON <i class="fa fa-angle-double-right" aria-hidden="true"></i> </span> CONTROLE INVENTAIRE
            </h4>
          </div>
          <hr />

          <!-- Liste des onglets  -->
          <div>
            <nav class="nav nav-tabs">
              <a class="nav-tab-item nav-item nav-link active" href="#Traitement" style="color: black;"> Traitement </a>
              <a class="nav-tab-item nav-item nav-link" href="#Resultat" style="color: black;"> Résultat <span class="badge badge-dark ml-2" style="background:red;border-radius:100%;display: none;" id="notif-Resultat-bell"><i class="far fa-bell"></i></span></a>
            </nav>
          </div>

          <!-- Content Row -->
          <div class="tab-content">
            <div class="tab-pane active" id="Traitement">
              <div class="row">
                <div class="col-xl-8 mt-4 mb-4">
                  <div class="card shadow mb-4">
                    <form method="post" action="#">
                      <div class="card-header py-3" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                        <h6 class="m-0 font-weight-bold text-white"> Liste des lots à traiter (id_lot)</h6>
                      </div>
                      <div class="card-body">
                        <div id="form-idlot-field" class="row">
                          <div class="form-group col-md-4">
                            <div class="form-group">
                              <textarea class="form-control" id="text-list-lot" rows="9" style="align-content:center; overflow:auto;" readonly="true">
                                    </textarea>
                            </div>
                          </div>
                          <div class="col-md-8">
                            <div class="d-flex justify-content-center mt-5">
                              <div id="txt-nb-lot" style="border: 1px solid gray;border-radius:100%;padding:35px;font-size:27px;">00</div>
                            </div>
                            <div class="d-flex justify-content-center mt-4">
                              <p id="txt-nb-lot-notif" text-std="Le Contrôle sera effectué sur ces lots"> </p>
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
                        <button class="btn btn-secondary" type="button" id="btn-reset-controle" data-dismiss="modal" disabled>Annuler</button>
                        <button type="submit" class="btn btn-dark" style="background:  <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;" id="btn-controle">
                          Lancer le Contrôle <span class="badge badge-success" style="font-size:15px;border-radius:100%;padding:5px;"><i class="fas fa-check-double"></i> </span>
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
                <div class="col-xl-4 mt-4 mb-4">
                  <div class="card shadow">
                    <div class="card-header py-3" style="background:white;">
                      <h6 class="m-0 font-weight-bold" style="color: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;"> Progression</h6>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <ol id="liste-indic">
                          <li class="mb-2" style="display: none;"> Traitement Table Inventaire_db <i class="fas fa-check text-success" style="margin-left:5px;font-size:20px;"></i> </li>
                          <li class="mb-2" style="display: none;"> Traitement Table TomeRegistre/Registre </li>
                          <li class="mb-2" style="display: none;"> Traitement Lot Introuvable </li>
                        </ol>
                      </div>
                    </div>
                    <div class="card-footer  bg-success" style="display: none;" id="indic-termine">
                      <div class="d-flex justify-content-center" style="font-size: 15px;color:white;">
                        Terminé <i class="fas fa-check-double" style="margin-left:12px;margin-top:2px;"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <a id="download" href="#" type="downlaod" style="display:none;"> télécharger </a>
            <div class="tab-pane" id="Resultat">
              <div id="resultat_data" class="row mt-3" style="display: none;">
                <div class="col-md-2">
                  <div class="form-group card shadow">
                    <div class="d-flex justify-content-center mb-1" style="color: white;background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;padding:7px;border-radius:5px;">
                      Lot Erronés (<span class="text-danger" id="indic-lot-error">0</span>)
                    </div>
                    <textarea class="form-control" id="text-list-lot-errone" style="align-content:center; overflow:auto;max-height:20em;height:20em;" readonly="true">
                    </textarea>
                  </div>
                </div>
                <div class="col-md-10">
                  <div class="mb-3">
                    <nav class="nav nav-tabs">
                      <a class="nav-tab-item nav-item nav-link active" href="#Inventaire_db" style="color: black;"> Table Inventaire_db <span class="badge badge-dark ml-2" style="background:red;border-radius:100%;" id="notif-Resultat-1"> 0 </span> <button id="Inventaire_db_dl" class="text-dark ml-2" style="background-color: transparent;border:none;"> <i class="fa fa-download" aria-hidden="true"></i> </button> </a>
                      <a class="nav-tab-item nav-item nav-link" href="#TomeRegistre" style="color: black;"> Table TomeRegistre/Registre <span class="badge badge-dark ml-2" style="background:red;border-radius:100%;" id="notif-Resultat-2"> 0 </span> <button id="TomeRegistre_dl" class="text-dark ml-2" style="background-color: transparent;border:none;"> <i class="fa fa-download" aria-hidden="true"></i> </button> </a>
                    </nav>
                  </div>

                  <div class="tab-content">
                    <!-- Table Inventaire_db -->
                    <div class="card shadow mb-4 tab-pane active" id="Inventaire_db">
                      <div class="card-header py-3" style="background:  <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                        <h6 class="m-0 font-weight-bold text-white"> Table Inventaire_db </h6>
                      </div>
                      <div class="card-body">
                        <div class="table-responsive">
                          <table class="table table-bordered" id="dataTableInventaire_db" width="100%" cellspacing="0">
                            <thead>
                              <tr>
                                <th> ID_LOT </th>
                                <th> INDICE_BD </th>
                                <th> INDICE_INV </th>
                                <th> TOME_BD </th>
                                <th> TOME_INV </th>
                                <th> G_BD </th>
                                <th> G_INV </th>
                                <th> H_BD </th>
                                <th> H_INV </th>
                                <th> NAISSANCE_DB </th>
                                <th> NAISSANCE_INV </th>
                                <th> DECES_DB </th>
                                <th> DECES_INV </th>
                                <th> MARIAGE_DB </th>
                                <th> MARIAGE_INV </th>
                                <th> DIVORCE_DB </th>
                                <th> DIVORCE_INV </th>
                                <th> ACTE_DB </th>
                                <th> ACTE_INV </th>
                              </tr>
                            </thead>
                            <tbody id="TableInventaire_db">

                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                    <!-- Table TomeRegistre/Registre -->
                    <div class="card shadow mb-4 tab-pane" id="TomeRegistre">
                      <div class="card-header py-3" style="background:  <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                        <h6 class="m-0 font-weight-bold text-white"> Table TomeRegistre/Registre </h6>
                      </div>
                      <div class="card-body">
                        <div class="table-responsive">
                          <table class="table table-bordered" id="dataTableTomeRegistre" width="100%" cellspacing="0">
                            <thead>
                              <tr>
                                <th> ID_LOT </th>
                                <th> INDICE_BD </th>
                                <th> INDICE_INV </th>
                                <th> TOME_BD </th>
                                <th> TOME_INV </th>
                                <th> G_BD </th>
                                <th> G_INV </th>
                                <th> H_BD </th>
                                <th> H_INV </th>
                                <th> NAISSANCE_DB </th>
                                <th> NAISSANCE_INV </th>
                                <th> DECES_DB </th>
                                <th> DECES_INV </th>
                                <th> MARIAGE_DB </th>
                                <th> MARIAGE_INV </th>
                                <th> DIVORCE_DB </th>
                                <th> DIVORCE_INV </th>
                                <th> ACTE_DB </th>
                                <th> ACTE_INV </th>
                              </tr>
                            </thead>
                            <tbody id="TableTomeRegistre">

                            </tbody>
                          </table>
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

    <!-- Page level custom scripts -->
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="js/owner/set_side_bar.js"></script>
    <script src="js/owner/page_indicateur.js"></script>
    <script src="js/owner/count_lot.js"></script>
    <script src="js/ajax/livraison/livraison_comparaison_inventaire.js?v1.0"></script>

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