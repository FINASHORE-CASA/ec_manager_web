<?php
require_once "is_connect.php";
require_once "./config/checkConfig.php";

$liste_champs_actes = ["num_acte", "annee_acte", "jd_naissance_h", "md_naissance_h", "ad_naissance_h", "jd_naissance_g", "md_naissance_g", "ad_naissance_g", "heure_naissance", "minute_naissance", "lieu_naissance", "jumeaux", "prenom_ar", "prenom_fr", "nom_ar", "nom_fr", "nom_marge_ar", "prenom_marge_fr", "nom_marge_fr", "prenom_marge_ar", "sexe", "id_nationlite", "decede_pere", "prenom_pere_ar", "prenom_pere_fr", "ascendant_pere_ar", "ascendant_pere_fr", "id_nationalite_pere", "id_profession_pere", "jd_naissance_pere_h", "md_naissance_pere_h", "ad_naissance_pere_h", "jd_naissance_pere_g", "md_naissance_pere_g", "ad_naissance_pere_g", "lieu_naissance_pere", "decede_mere", "prenom_mere_ar", "prenom_mere_fr", "ascendant_mere_ar", "ascendant_mere_fr", "id_nationalite_mere", "id_profession_mere", "jd_naissance_mere_h", "md_naissance_mere_h", "ad_naissance_mere_h", "jd_naissance_mere_g", "md_naissance_mere_g", "ad_naissance_mere_g", "lieu_naissance_mere", "adresse_residence_parents", "jd_etabli_acte_h", "ad_etabli_acte_h", "md_etabli_acte_h", "jd_etabli_acte_g", "ad_etabli_acte_g", "md_etabli_acte_g", "id_officier", "annee_acte_g", "lieu_naissance_fr", "lieu_naissance_pere_fr", "lieu_naissance_mere_fr", "adresse_residence_parents_fr", "sign_officier", "sceau_officier", "status_acte", "heure_etabli_acte", "minute_etabli_acte", "date_creation", "status_acteechantillon", "langue_acte", "id_ville_naissance", "id_ville_naissance_mere", "id_ville_naissance_pere", "id_ville_residence_parents", "date_statut_oec", "imagepath", "remarque"];

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
  <link href="css/sb-admin-2.min.css" rel="stylesheet" />
  <link href="css/bootstrap-select.min.css" rel="stylesheet" />
  <link href="css/modal-fullscreen.css" rel="stylesheet" />

  <!-- Custom styles for this page -->
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" />
</head>

<body id="page-top" idpage="ActionIEC">

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
              <div class="modal-header" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                <h5 class="modal-title" id="exampleModalLabel" style="color: white;"> Formulaire de modification Acte </h5>
                <div style="position:absolute;right:5px;">
                  <button type="button" class="btn btn-success form-update-save" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                    Enregistrer <i class="fa fa-check-circle" aria-hidden="true"></i>
                  </button>
                  <button id="Form-extand" type="button" class="btn btn-default text-white ml-2" is_active="false">
                    <i class="fas fa-expand"></i>
                  </button>
                  <button type="button" class="btn btn-default btn-form-modal-cancel text-danger" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times-circle"></i>
                  </button>
                </div>
              </div>
              <div class="modal-body">
                <div class="row" id="form-acte-field">
                  <div class="col-md-6" style="height:700px;overflow:auto;">
                    <!-- Liste des onglets  -->
                    <div>
                      <nav class="nav nav-tabs">
                        <a id="btnTabActive" class="nav-tab-item nav-item nav-link active" href="#acte" style="color: black;"> Champs Acte </a>
                      </nav>
                    </div>
                    <div class="tab-content">
                      <div class="tab-pane active" id="acte">
                        <form class="mt-2">
                          <input type="hidden" id="field-Id_user" value="<?= isset($_SESSION['user']) ? $_SESSION['user']->id_user : '' ?>" />
                          <input type="hidden" id="field-Id_user_saisi" value="" />
                          <div class="row">
                            <div class="form-group col-md-6">
                              <label for="field-id_lot">Id Lot</label>
                              <input type="text" class="form-control" id="field-id_lot" aria-describedby="field-id_lot" placeholder="" disabled />
                            </div>
                            <div class="form-group col-md-6">
                              <label for="field-id_acte">Id Acte</label>
                              <input type="text" class="form-control" id="field-id_acte" aria-describedby="field-id_acte" placeholder="" disabled />
                            </div>
                          </div>
                          <div id="form-fields-fillables">

                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="row d-flex justify-content-center">
                      <div style="height:50px;" class="block-img-change"></div>
                    </div>
                    <div class="row">
                      <div id="img-block" style="min-height: 600px;"></div>
                    </div>
                    <div class="row d-flex justify-content-center py-2">
                      <div style="height:50px;" class="block-img-change"></div>
                    </div>
                  </div>
                </div>
                <div class="row" id="form-acte-loader" style="position:absolute;width:99%;height:100%;opacity:0.9;top:0px;background:white;padding:0px;">
                  <div class="d-flex justify-content-center" style="padding:15em;width:100%">
                    <img src="./img/loader.gif" alt="loader wait" style="height: 80px;width:80px;padding:0px;" />
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-form-modal-cancel" data-dismiss="modal"> Annuler</button>
                <button type="button" class="btn btn-primary form-update-save" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;"> Enregistrer <i class="far fa-save ml-1"></i></button>
              </div>
            </div>
          </div>
        </div>

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <h4 class="h6 mb-0 text-dark-800">
              <span style="color:<?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;"> ACTION MI <i class="fa fa-angle-double-right" aria-hidden="true"></i> </span> CONTROLE UNITAIRE
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
                              <textarea class="form-control" id="text-list-lot" rows="9" style="align-content:center; overflow:auto;">
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
                        <div class="form-inline ml-3">
                          <label for="show_all"> Selectionner tous les lots </label>
                          <input type="checkbox" class="form-control ml-1" id="show_all" />
                        </div>
                        <hr />
                        <div class="form-inline ml-3">
                          <label for="list_champs"> listes champs : </label>
                          <select class="selectpicker" id="list_champs" name="list_champs" width="100%" multiple>
                            <?php
                            foreach ($liste_champs_actes as $value) {
                              echo '<option>' . $value . '</option>';
                            }
                            ?>
                          </select>
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
                      <h6 class="m-0 font-weight-bold" style="color:  <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;"> Progression</h6>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <ol id="liste-indic">
                          <li class="mb-2" style="display: none;"> Vérification des identités <i class="fas fa-check text-success" style="margin-left:5px;font-size:20px;"></i> </li>
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

            <div class="tab-pane" id="Resultat">
              <div id="alert-container"></div>
              <div id="resultat_data" class="row mt-3">
                <div class="col-md-12">
                  <div class="card shadow mb-4 tab-pane active" id="ListeActes">
                    <div class="card-header py-3" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                      <h6 class="m-0 font-weight-bold text-white"> Liste des Actes </h6>
                    </div>
                    <div class="card-body" id="table_container">
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
  <script src="js/modal-fullscreen.js"></script>
  <script src="js/owner/count_lot.js"></script>
  <!-- next version -- 1.0.1   -->
  <script src="js/ajax/actioniec/actioniec_controle_unitaire.js?version=1.0.3"></script>

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