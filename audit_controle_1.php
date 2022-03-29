<?php
require_once "is_connect.php";
require_once "./config/checkConfig.php";

$liste_champs_actes = ["num_acte", "annee_acte", "jd_naissance_h", "md_naissance_h", "ad_naissance_h", "jd_naissance_g", "md_naissance_g", "ad_naissance_g", "heure_naissance", "minute_naissance", "lieu_naissance", "jumeaux", "prenom_ar", "prenom_fr", "nom_ar", "nom_fr", "nom_marge_ar", "prenom_marge_fr", "nom_marge_fr", "prenom_marge_ar", "sexe", "id_nationlite", "decede_pere", "prenom_pere_ar", "prenom_pere_fr", "ascendant_pere_ar", "ascendant_pere_fr", "id_nationalite_pere", "id_profession_pere", "jd_naissance_pere_h", "md_naissance_pere_h", "ad_naissance_pere_h", "jd_naissance_pere_g", "md_naissance_pere_g", "ad_naissance_pere_g", "lieu_naissance_pere", "decede_mere", "prenom_mere_ar", "prenom_mere_fr", "ascendant_mere_ar", "ascendant_mere_fr", "id_nationalite_mere", "id_profession_mere", "jd_naissance_mere_h", "md_naissance_mere_h", "ad_naissance_mere_h", "jd_naissance_mere_g", "md_naissance_mere_g", "ad_naissance_mere_g", "lieu_naissance_mere", "adresse_residence_parents", "jd_etabli_acte_h", "ad_etabli_acte_h", "md_etabli_acte_h", "jd_etabli_acte_g", "ad_etabli_acte_g", "md_etabli_acte_g", "id_officier", "annee_acte_g", "lieu_naissance_fr", "lieu_naissance_pere_fr", "lieu_naissance_mere_fr", "adresse_residence_parents_fr", "sign_officier", "sceau_officier", "status_acte", "heure_etabli_acte", "minute_etabli_acte", "status_acteechantillon", "langue_acte", "id_ville_naissance", "id_ville_naissance_mere", "id_ville_naissance_pere", "id_ville_residence_parents", "date_statut_oec", "imagepath", "remarque"];
$liste_champs_deces = ["jd_deces_h", "md_deces_h", "ad_deces_h", "jd_deces_g", "md_deces_g", "ad_deces_g", "heure_deces", "minute_deces", "lieu_deces", "id_profession", "statutfamilialle", "lieuresidence", "lieuresidence_fr", "lieu_deces_fr", "lieu_residence_pere_ar", "lieu_residence_pere_fr", "lieu_residence_mere_ar", "lieu_residence_mere_fr", "id_ville_deces", "id_ville_adresse_mere", "id_ville_adresse_pere", "id_ville_adresse"];
$liste_champs_jugement = ["num_jugement", "num_dossier", "annee_dossier", "jd_etablissement_jugement_h", "md_etablissement_jugement_h", "ad_etablissement_jugement_h", "jd_etablissement_jugement_g", "md_etablissement_jugement_g", "ad_etablissement_jugement_g", "j_prononciation_jugement_g", "md_prononciation_jugement_g", "ad_prononciation_jugement_g", "j_prononciation_jugement_h", "md_prononciation_jugement_h", "ad_prononciation_jugement_h", "j_reception_jugement_g", "md_reception_jugement_g", "ad_reception_jugement_g", "j_reception_jugement_h", "md_reception_jugement_h", "ad_reception_jugement_h", "denominationjugement", "dispositifjugement", "objetjugement", "signofficier", "objetjugement_fr", "is_collectif"];
$liste_champs_mention = ["jd_memtion_h", "md_memtion_h", "a_memtion_h", "jd_memtion_g", "md_memtion_g", "ad_memtion_g", "txtmention", "status_mention", "sign_officier", "txtmention_fr", "nouvelle_valeur", "ancienne_valeur", "nouvelle_valeur_fr", "ancienne_valeur_fr", "modifmention"];

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

<body id="page-top" idpage="Audit">

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

        <!-- Formulaire Affectation Lot Modal -->

        <!-- Modal -->
        <div class="modal fade" id="ActeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
          <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
              <div class="modal-header" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                <h5 class="modal-title" id="exampleModalLabel" style="color: white;"> Contrôle Acte </h5>
                <div style="position:absolute;right:5px;">
                  <button type="button" class="btn btn-success form-controle-save" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                    Valider <i class="fa fa-check-circle" aria-hidden="true"></i>
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
                            <div class="form-group col-md-3">
                              <label for="field-id_acte">Id Acte</label>
                              <input type="text" class="form-control" id="field-id_acte" aria-describedby="field-id_acte" placeholder="" disabled />
                            </div>
                            <div class="form-group col-md-3">
                              <label for="field-id_user_traitement"> AgentID</label>
                              <input type="text" class="form-control" id="field-id_user_traitement" aria-describedby="field-id_user_traitement" placeholder="" disabled />
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
                <button type="button" class="btn btn-primary form-controle-save" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;"> Valider <i class="fa fa-check-circle ml-1"></i></button>
              </div>
            </div>
          </div>
        </div>

        <!-- Formulaire liste Lot Modal -->
        <div class="modal fade" id="listLotModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                <h5 class="modal-title" style="color: white;"> Liste lot <span id="list-all-lot-dispo-nb" class="badge badge-light ml-2"> 0 </span> </h5>
              </div>
              <div class="modal-body">
                <textarea id="list-all-lot-dispo" class="form-control" name="" rows="7" readonly="true"></textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <h4 class="h6 mb-0 text-dark-800">
              <span style="color:<?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;"> AUDIT <i class="fa fa-angle-double-right" aria-hidden="true"></i> </span> AUDIT CONTROLE 1
            </h4>
          </div>
          <hr />

          <!-- lien de téléchargement -->
          <a id="download" href="#" type="download" style="display:none;"> télécharger </a>

          <!-- Content Row -->
          <div class="tab-content">
            <div class="row">
              <div class="col-xl-3 mt-4 mb-4">
                <div id="card-list-lot-audit">
                  <div class="card shadow mb-4">
                    <div class="card-header py-3">
                      <h6 class="m-0 font-weight-bold text-primary"> Liste Lot <span id="nb-liste-lot-agent" class="ml-2 badge badge-primary"> 0 </span> </h6>
                    </div>
                    <div class="card-body">
                      <div class="mb-4">
                        <input class="form-control" type="text" placeholder="rechercher" />
                      </div>
                      <div class="table-responsive" style="overflow:auto;max-height:250px;">
                        <table class="table" id="dataTableLotAgentDispo" width="100%" cellspacing="0">
                          <tbody id="TableAgentLotAgentDispo">

                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>

                  <div class="card shadow mb-4">
                    <div class="card-body">
                      <div class="form-inline ml-3">
                        <label for="ech_value"> % échantillonnage </label>
                        <input type="number" id="ech_value" class="form-control ml-3" style="width:70px;" step="5" value="20" />
                      </div>
                      <hr />
                      <div class="form-inline ml-3">
                        <label for="list_champs"> listes champs : </label>
                        <select class="selectpicker" id="list_champs" name="list_champs" width="100%" multiple>
                          <optgroup label="Acte">
                            <?php
                            foreach ($liste_champs_actes as $value) {
                              echo '<option>' . $value . '</option>';
                            }
                            ?>
                          </optgroup>
                          <optgroup label="Deces">
                            <?php
                            foreach ($liste_champs_deces as $value) {
                              echo '<option>' . $value . '</option>';
                            }
                            ?>
                          </optgroup>
                          <optgroup label="Jugement">
                            <?php
                            foreach ($liste_champs_jugement as $value) {
                              echo '<option>' . $value . '</option>';
                            }
                            ?>
                          </optgroup>
                          <optgroup label="Mention">
                            <?php
                            foreach ($liste_champs_mention as $value) {
                              echo '<option>' . $value . '</option>';
                            }
                            ?>
                          </optgroup>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="card shadow mb-4">
                    <div class="card-body">
                      <a href="#" id="btn-lunch-audit" class="btn btn-success" style="width:100%;"> Auditer <i class="fas fa-flag-checkered"></i> </a>
                    </div>
                  </div>
                </div>

                <div id="card-details-lot-auditer" style="display:none;">
                  <div class="card shadow mb-4">
                    <div class="card-header py-3">
                      <h6 id="details-lot-id_lot" class="m-0 font-weight-bold text-primary"> </h6>
                    </div>
                  </div>

                  <div class="card shadow mb-4">
                    <div class="card-header py-3">
                      <h6 class="m-0 font-weight-bold text-primary"> Stats Audit </h6>
                    </div>
                    <div class="card-body">
                      <p> Actes Audités : <span class="details-lot-nb_acte_audite"> 0 </span> / <span class="details-lot-nb_acte_ech"> 0 </span> </p>
                      <hr>
                      <p> Actes Acceptés : <span class="details-lot-nb_acte_accepte"> 0 </span> / <span class="details-lot-nb_acte_audite"> 0 </span> </p>
                      <hr>
                      <p> Actes Réjétés : <span class="details-lot-nb_acte_rejete"> 0 </span> / <span class="details-lot-nb_acte_audite"> 0 </span> </p>
                    </div>
                  </div>

                  <div class="card shadow mb-4">
                    <div class="card-body">
                      <a href="#" id="btn-retour-list-audit" class="btn btn-default" style="width:20%;"> <i class="fa fa-list"></i> </a>
                      <a href="#" id="btn-valid-audit-lot" class="btn btn-success" style="width:77%;"> Valider <i class="fas fa-check-double"></i> </a>
                    </div>
                  </div>
                </div>

                <div id="card-loader-lot-audit" style="display:none;">
                  <div class="text-center mt-5">
                    <i class="fa fa-spinner fa-spin" style="font-size:2rem;"> </i>
                  </div>
                </div>

              </div>

              <div class="col-xl-9 mt-4 mb-4">
                <div id="alert-container"></div>
                <div class="card shadow mb-4">
                  <form method="post" action="#">
                    <div class="card-header py-3" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                      <h6 class="m-0 font-weight-bold text-white"> Liste des actes
                      </h6>
                    </div>
                    <div class="card-body" id="table_container">

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
  <script src="js/owner/count_lot.js"></script>
  <script src="js/modal-fullscreen.js"></script>
  <!-- next version -- 1.0.1   -->
  <script src="js/ajax/audit/audit_controle1.js?v=1.0.3"></script>

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