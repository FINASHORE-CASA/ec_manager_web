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
  <link href="css/modal-fullscreen.css" rel="stylesheet" />

  <!-- Custom styles for this page -->
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top" idpage="GestionStats">

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
                  <button type="button" class="btn btn-success ml-3 form-update-save" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
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
                        <a id="btnTabActive" class="nav-tab-item nav-item nav-link active" href="#acte" style="color: black;"> Critère Acte </a>
                        <a id="btnTabMention" class="nav-tab-item nav-item nav-link" href="#mention" style="color: black;display:none;"> Critère mention </a>
                      </nav>
                    </div>
                    <div class="tab-content">
                      <div class="tab-pane active" id="acte">
                        <form class="mt-2">
                          <input type="hidden" id="field-Id_user" value="<?= isset($_SESSION['user']) ? $_SESSION['user']->id_user : '' ?>" />
                          <input type="hidden" id="field-Id_user_saisi" value="" />
                          <div class="row">
                            <div class="form-group col-md-5">
                              <label for="field-IdLot">Id Lot</label>
                              <input type="text" class="form-control" id="field-IdLot" aria-describedby="field-IdLot" placeholder="" disabled />
                            </div>
                            <div class="form-group col-md-3">
                              <label for="field-IdActe">Id Acte</label>
                              <input type="text" class="form-control" id="field-IdActe" aria-describedby="field-IdActe" placeholder="" disabled />
                            </div>
                            <div class="form-group col-md-3">
                              <label for="field-NbMention">mention</label>
                              <input type="text" class="form-control" id="field-NbMention" aria-describedby="field-NbMention" placeholder="" disabled />
                            </div>
                          </div>
                          <hr />
                          <div id="form-group1" style="margin: 0;padding: 10px;">
                            <div class="row">
                              <div class="form-group col-md-4">
                                <label for="field-jour_h">jour_naissance_h</label>
                                <input type="text" class="form-control" id="field-jour_h" aria-describedby="field-jour_h" placeholder="" />
                              </div>
                              <div class="form-group col-md-4">
                                <label for="field-mois_h">mois_naissance_h</label>
                                <input type="text" class="form-control" id="field-mois_h" aria-describedby="field-mois_h" placeholder="" />
                              </div>
                              <div class="form-group col-md-4">
                                <label for="field-annee_h">annee_naissance_h</label>
                                <input type="text" class="form-control" id="field-annee_h" aria-describedby="field-annee_h" placeholder="" />
                              </div>
                            </div>
                            <div class="row">
                              <div class="form-group col-md-4">
                                <label for="field-jour_g">jour_naissance_g</label>
                                <input type="text" class="form-control" id="field-jour_g" aria-describedby="field-jour_g" placeholder="" />
                              </div>
                              <div class="form-group col-md-4">
                                <label for="field-mois_g">mois_naissance_g</label>
                                <input type="text" class="form-control" id="field-mois_g" aria-describedby="field-mois_g" placeholder="" />
                              </div>
                              <div class="form-group col-md-4">
                                <label for="field-annee_g">annee_naissance_g</label>
                                <input type="text" class="form-control" id="field-annee_g" aria-describedby="field-annee_g" placeholder="" />
                              </div>
                            </div>
                          </div>
                          <hr />
                          <div id="form-group2" style="margin: 0;padding: 10px;">
                            <div class="row">
                              <div class="form-group col-md-5">
                                <label for="field-PrenomFr">Prenom fr</label>
                                <input type="text" class="form-control" id="field-PrenomFr" aria-describedby="field-PrenomFr" placeholder="" />
                              </div>
                              <div class="form-group col-md-5">
                                <label for="field-PrenomAr">Prenom Ar</label>
                                <input type="text" class="form-control" id="field-PrenomAr" aria-describedby="field-PrenomAr" placeholder="" />
                              </div>
                            </div>
                            <div class="row">
                              <div class="form-group col-md-5">
                                <label for="field-PrenomMargeFr">Prenom marge fr</label>
                                <input type="text" class="form-control" id="field-PrenomMargeFr" aria-describedby="field-PrenomMargeFr" placeholder="" />
                              </div>
                              <div class="form-group col-md-5">
                                <label for="field-PrenomMargeAr">Prenom marge ar</label>
                                <input type="text" class="form-control" id="field-PrenomMargeAr" aria-describedby="field-PrenomMargeAr" placeholder="" />
                              </div>
                            </div>
                            <div class="row">
                              <div class="form-group col-md-5">
                                <label for="field-Genre"> Genre </label>
                                <input type="text" class="form-control" id="field-Genre" aria-describedby="field-Genre" placeholder="" />
                              </div>
                              <div class="col-md-2">
                                <button type="button" class="btn btn-success col-md-12" style="background: none;color:#1cc88a;margin-top:2em;" id="btn-add-champs-prenom-genre">
                                  <span class="fas fa-check-double"></span>
                                </button>
                              </div>
                            </div>
                          </div>
                          <hr />
                          <div id="form-group3" style="margin: 0;padding: 10px;">
                            <div class="row">
                              <div class="form-group col-md-5">
                                <label for="field-NomFr">Nom fr</label>
                                <input type="text" class="form-control" id="field-NomFr" aria-describedby="field-NomFr" placeholder="" />
                              </div>
                              <div class="form-group col-md-5">
                                <label for="field-NomAr">Nom ar</label>
                                <input type="text" class="form-control" id="field-NomAr" aria-describedby="field-NomAr" placeholder="" />
                              </div>
                            </div>
                            <div class="row">
                              <div class="form-group col-md-5">
                                <label for="field-NomMargeFr">Nom marge fr</label>
                                <input type="text" class="form-control" id="field-NomMargeFr" aria-describedby="field-NomMargeFr" placeholder="" />
                              </div>
                              <div class="form-group col-md-5">
                                <label for="field-NomMargeAr">Nom marge ar</label>
                                <input type="text" class="form-control" id="field-NomMargeAr" aria-describedby="field-NomMargeAr" placeholder="" />
                              </div>
                            </div>
                          </div>
                          <hr />
                          <div id="form-group4" style="margin: 0;padding: 10px;">
                            <div class="row">
                              <div class="form-group col-md-5">
                                <label for="field-PrenomPereFr">Prenom père fr</label>
                                <input type="text" class="form-control" id="field-PrenomPereFr" aria-describedby="field-PrenomPereFr" placeholder="" />
                              </div>
                              <div class="form-group col-md-5">
                                <label for="field-PrenomPereAr">Prenom père ar</label>
                                <input type="text" class="form-control" id="field-PrenomPereAr" aria-describedby="field-PrenomPereAr" placeholder="" />
                              </div>
                              <div class="form-group col-md-2">
                                <button type="button" class="btn btn-success col-md-12" style="background: none;color:#1cc88a;margin-top:2em;" id="btn-add-champs-prenom-pere">
                                  <span class="fas fa-check-double"></span>
                                </button>
                              </div>
                            </div>
                            <div class="row">
                              <div class="form-group col-md-5">
                                <label for="field-AscPereFr">Ascendant père fr</label>
                                <input type="text" class="form-control" id="field-AscPereFr" aria-describedby="field-AscPereFr" placeholder="" />
                              </div>
                              <div class="form-group col-md-5">
                                <label for="field-AscPereAr">Ascendant père ar</label>
                                <input type="text" class="form-control" id="field-AscPereAr" aria-describedby="field-AscPereAr" placeholder="" />
                              </div>
                            </div>
                          </div>
                          <hr />
                          <div id="form-group5" style="margin: 0;padding: 10px;">
                            <div class="row">
                              <div class="form-group col-md-5">
                                <label for="field-PrenomMereFr">Prenom mère fr</label>
                                <input type="text" class="form-control" id="field-PrenomMereFr" aria-describedby="field-PrenomMereFr" placeholder="" />
                              </div>
                              <div class="form-group col-md-5">
                                <label for="field-PrenomMereAr">Prenom mère ar</label>
                                <input type="text" class="form-control" id="field-PrenomMereAr" aria-describedby="field-PrenomMereAr" placeholder="" />
                              </div>
                              <div class="form-group col-md-2">
                                <button type="button" class="btn btn-success col-md-12" style="background: none;color:#1cc88a;margin-top:2em;" id="btn-add-champs-prenom-mere">
                                  <span class="fas fa-check-double"></span>
                                </button>
                              </div>
                            </div>
                            <div class="row">
                              <div class="form-group col-md-5">
                                <label for="field-AscMereFr">Ascendant mère fr</label>
                                <input type="text" class="form-control" id="field-AscMereFr" aria-describedby="field-AscMereFr" placeholder="" />
                              </div>
                              <div class="form-group col-md-5">
                                <label for="field-AscMereAr">Ascendant mère ar</label>
                                <input type="text" class="form-control" id="field-AscMereAr" aria-describedby="field-AscMereAr" placeholder="" />
                              </div>
                            </div>
                          </div>
                          <hr />
                          <div id="form-group6" style="margin: 0;padding: 10px;">
                            <div class="row">
                              <div class="form-group col-md-4">
                                <label for="field-jd_etabli_acte_h">jd_etabli_acte_h</label>
                                <input type="text" class="form-control" id="field-jd_etabli_acte_h" aria-describedby="field-jd_etabli_acte_h" placeholder="" />
                              </div>
                              <div class="form-group col-md-4">
                                <label for="field-md_etabli_acte_h">md_etabli_acte_h</label>
                                <input type="text" class="form-control" id="field-md_etabli_acte_h" aria-describedby="field-md_etabli_acte_h" placeholder="" />
                              </div>
                              <div class="form-group col-md-4">
                                <label for="field-ad_etabli_acte_h">ad_etabli_acte_h</label>
                                <input type="text" class="form-control" id="field-ad_etabli_acte_h" aria-describedby="field-ad_etabli_acte_h" placeholder="" />
                              </div>
                            </div>
                            <div class="row">
                              <div class="form-group col-md-4">
                                <label for="field-jd_etabli_acte_g">jd_etabli_acte_g</label>
                                <input type="text" class="form-control" id="field-jd_etabli_acte_g" aria-describedby="field-jd_etabli_acte_g" placeholder="" />
                              </div>
                              <div class="form-group col-md-4">
                                <label for="field-md_etabli_acte_g">md_etabli_acte_g</label>
                                <input type="text" class="form-control" id="field-md_etabli_acte_g" aria-describedby="field-md_etabli_acte_g" placeholder="" />
                              </div>
                              <div class="form-group col-md-4">
                                <label for="field-ad_etabli_acte_g">ad_etabli_acte_g</label>
                                <input type="text" class="form-control" id="field-ad_etabli_acte_g" aria-describedby="field-ad_etabli_acte_g" placeholder="" />
                              </div>
                            </div>
                          </div>
                          <hr />
                          <div id="form-group7" style="margin: 0;padding: 10px;display:none;">
                            <div class="row">
                              <div class="form-group col-md-4">
                                <label for="field-jd_deces_h">jd_deces_h</label>
                                <input type="text" class="form-control" id="field-jd_deces_h" aria-describedby="field-jd_deces_h" placeholder="" />
                              </div>
                              <div class="form-group col-md-4">
                                <label for="field-md_deces_h">md_deces_h</label>
                                <input type="text" class="form-control" id="field-md_deces_h" aria-describedby="field-md_deces_h" placeholder="" />
                              </div>
                              <div class="form-group col-md-4">
                                <label for="field-ad_deces_h">ad_deces_h</label>
                                <input type="text" class="form-control" id="field-ad_deces_h" aria-describedby="field-ad_deces_h" placeholder="" />
                              </div>
                            </div>
                            <div class="row">
                              <div class="form-group col-md-4">
                                <label for="field-jd_deces_g">jd_deces_g</label>
                                <input type="text" class="form-control" id="field-jd_deces_g" aria-describedby="field-jd_deces_g" placeholder="" />
                              </div>
                              <div class="form-group col-md-4">
                                <label for="field-md_deces_g">md_deces_g</label>
                                <input type="text" class="form-control" id="field-md_deces_g" aria-describedby="field-md_deces_g" placeholder="" />
                              </div>
                              <div class="form-group col-md-4">
                                <label for="field-ad_deces_g">ad_deces_g</label>
                                <input type="text" class="form-control" id="field-ad_deces_g" aria-describedby="field-ad_deces_g" placeholder="" />
                              </div>
                            </div>
                          </div>
                        </form>
                      </div>
                      <div class="tab-pane" id="mention">
                        <div class="row ml-2 mt-3">
                        </div>
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
              <span style="color:<?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;"> GESTION STATS <i class="fa fa-angle-double-right" aria-hidden="true"></i> </span> STATS CONTROLE
            </h4>
          </div>
          <hr />

          <!-- Liste des onglets  -->
          <div>
            <nav class="nav nav-tabs">
              <a class="nav-tab-item nav-item nav-link active" href="#Traitement" style="color: black;"> Liste Lots </a>
              <a class="nav-tab-item nav-item nav-link" href="#Resultat" style="color: black;"> Données Stats <span class="badge badge-dark ml-2" style="background:red;border-radius:100%;display: none;" id="notif-Resultat-bell"><i class="far fa-bell"></i></span></a>
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
                        <h6 class="m-0 font-weight-bold text-white"> Liste des lots à générer</h6>
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
                          Générer les fichiers <span class="badge badge-success" style="font-size:15px;border-radius:100%;padding:5px;"><i class="fas fa-download"></i> </span>
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

            <a id="download" href="#" type="downlaod" style="display:none;"> télécharger </a>
            <div class="tab-pane" id="Resultat">
              <div id="alert-container"></div>
              <div id="resultat_data" class="row mt-3" style="display: none;">
                <div class="col-md-12">
                  <div class="mb-3">
                    <nav class="nav nav-tabs">
                      <a class="nav-tab-item nav-item nav-link active" href="#StatsControleUnitaire" style="color: black;"> Stats Contrôle Unitaire <span class="badge badge-dark ml-2" style="background:red;border-radius:100%;" id="notif-Resultat-1"> 0 </span> <button id="StatsControleUnitaire_dl" class="text-dark ml-2" style="background-color: transparent;border:none;"> <i class="fa fa-download" aria-hidden="true"></i> </button></a>
                    </nav>
                  </div>

                  <div class="tab-content">

                    <!-- Stats Contrôle Unitaire  -->
                    <div class="card shadow mb-4 tab-pane active" id="StatsControleUnitaire">
                      <div class="card-header py-3" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                        <h6 class="m-0 font-weight-bold text-white"> Stats Contrôle Unitaire </h6>
                      </div>
                      <div class="card-body">
                        <div class="table-responsive">
                          <table class="table table-bordered" id="dataTableStatsControleUnitaire" width="100%" cellspacing="0">
                            <thead>
                              <tr>
                                <th> Lot </th>
                                <th> Login </th>
                                <th> Nombre actes </th>
                                <th> Nombre actes Controlés </th>
                                <th> Date Controle </th>
                              </tr>
                            </thead>
                            <tbody id="TableStatsControleUnitaire">

                            </tbody>
                            <tfoot>
                              <th> Lot </th>
                              <th> Login </th>
                              <th> Nombre actes </th>
                              <th> Nombre actes Controlés </th>
                              <th> Date Controle </th>
                            </tfoot>
                          </table>
                        </div>
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
  <script src="js/panzoom.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="vendor/chart.js/Chart.min.js"></script>
  <script src="js/owner/set_side_bar.js"></script>
  <script src="js/owner/page_indicateur.js"></script>
  <script src="js/modal-fullscreen.js"></script>
  <script src="js/owner/count_lot.js"></script>
  <!-- next version -- 1.0.3   -->
  <script src="js/ajax/stats/stats_controle.js?version=1.0.2"></script>

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