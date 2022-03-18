<div class="modal fade" id="ActeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">
                <h5 class="modal-title" id="exampleModalLabel" style="color: white;"> Modification Acte du lot : <strong> <span id="field-Id_lot"></span> </strong> </h5>
                <div style="position:absolute;right:5px;">
                    <button type="button" class="btn btn-success ml-5 form-correct-btn">
                        Déjà Correct <i class="fas fa-check-double"></i>
                    </button>
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
                    <!-- Content Row -->
                    <div class="col-md-6" style="height:700px;overflow:auto;">
                        <!-- Liste des onglets  -->
                        <div>
                            <nav class="nav nav-tabs">
                                <a class="nav-tab-item nav-item nav-link active" href="#ARHP" style="color: black;"> Infos Arhp </a>
                                <a class="nav-tab-item nav-item nav-link" href="#Personne" style="color: black;"> Personne </a>
                                <a class="nav-tab-item nav-item nav-link" href="#Pere" style="color: black;"> Père </a>
                                <a class="nav-tab-item nav-item nav-link" href="#Mere" style="color: black;"> Mère </a>
                                <a class="nav-tab-item nav-item nav-link" href="#Declaration" style="color: black;"> Déclaration </a>
                            </nav>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane active" id="ARHP">
                                <form class="mt-2">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-id_acte"> id_acte </label>
                                            <input type="text" class="form-control" id="field-id_acte" aria-describedby="field-id_acte" placeholder="" disabled />
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-id_type_acte"> id_type_acte </label>
                                            <input type="text" class="form-control" id="field-id_type_acte" aria-describedby="field-id_type_acte" placeholder="" disabled />
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-id_tome_registre"> id_tome_registre </label>
                                            <input type="text" class="form-control" id="field-id_tome_registre" aria-describedby="field-id_tome_registre" placeholder="" disabled />
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-num_acte"> num_acte </label>
                                            <input type="text" class="form-control" id="field-num_acte" aria-describedby="field-num_acte" placeholder="">
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-status_acte"> status_acte </label>
                                            <input type="text" class="form-control" id="field-status_acte" aria-describedby="field-status_acte" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-utilisateur_creation"> utilisateur_creation </label>
                                            <input type="text" class="form-control" id="field-utilisateur_creation" aria-describedby="field-utilisateur_creation" placeholder="" disabled />
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-date_creation"> date_creation </label>
                                            <input type="text" class="form-control" id="field-date_creation" aria-describedby="field-date_creation" placeholder="" disabled />
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-utilisateur_modification"> utilisateur_modification </label>
                                            <input type="text" class="form-control" id="field-utilisateur_modification" aria-describedby="field-utilisateur_modification" placeholder="" disabled />
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-langue_acte"> langue_acte </label>
                                            <input type="text" class="form-control" id="field-langue_acte" aria-describedby="field-langue_acte" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-imagepath"> imagepath </label>
                                            <input type="text" class="form-control" id="field-imagepath" aria-describedby="field-imagepath" placeholder="">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="Personne">
                                <h6 class="mt-2"> <strong>Acte</strong> </h6>
                                <hr />
                                <form class="mt-1">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-jd_naissance_h"> jd_naissance_h </label>
                                            <input type="text" class="form-control" id="field-jd_naissance_h" aria-describedby="field-jd_naissance_h" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-md_naissance_h"> md_naissance_h </label>
                                            <input type="text" class="form-control" id="field-md_naissance_h" aria-describedby="field-md_naissance_h" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-ad_naissance_h"> ad_naissance_h </label>
                                            <input type="text" class="form-control" id="field-ad_naissance_h" aria-describedby="field-ad_naissance_h" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-jd_naissance_g"> jd_naissance_g </label>
                                            <input type="text" class="form-control" id="field-jd_naissance_g" aria-describedby="field-jd_naissance_g" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-md_naissance_g"> md_naissance_g </label>
                                            <input type="text" class="form-control" id="field-md_naissance_g" aria-describedby="field-md_naissance_g" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-ad_naissance_g"> ad_naissance_g </label>
                                            <input type="text" class="form-control" id="field-ad_naissance_g" aria-describedby="field-ad_naissance_g" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-heure_naissance"> heure_naissance </label>
                                            <input type="text" class="form-control" id="field-heure_naissance" aria-describedby="field-heure_naissance" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-minute_naissance"> minute_naissance </label>
                                            <input type="text" class="form-control" id="field-minute_naissance" aria-describedby="field-minute_naissance" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-prenom_ar"> prenom_ar </label>
                                            <input type="text" class="form-control" id="field-prenom_ar" aria-describedby="field-prenom_ar" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-nom_ar"> nom_ar </label>
                                            <input type="text" class="form-control" id="field-nom_ar" aria-describedby="field-nom_ar" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-prenom_marge_ar"> prenom_marge_ar </label>
                                            <input type="text" class="form-control" id="field-prenom_marge_ar" aria-describedby="field-prenom_marge_ar" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-nom_marge_ar"> nom_marge_ar </label>
                                            <input type="text" class="form-control" id="field-nom_marge_ar" aria-describedby="field-nom_marge_ar" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-prenom_fr"> prenom_fr </label>
                                            <input type="text" class="form-control" id="field-prenom_fr" aria-describedby="field-prenom_fr" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-nom_fr"> nom_fr </label>
                                            <input type="text" class="form-control" id="field-nom_fr" aria-describedby="field-nom_fr" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-prenom_marge_fr"> prenom_marge_fr </label>
                                            <input type="text" class="form-control" id="field-prenom_marge_fr" aria-describedby="field-prenom_marge_fr" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-nom_marge_fr"> nom_marge_fr </label>
                                            <input type="text" class="form-control" id="field-nom_marge_fr" aria-describedby="field-nom_marge_fr" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-sexe"> sexe </label>
                                            <input type="text" class="form-control" id="field-sexe" aria-describedby="field-sexe" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-id_nationlite"> id_nationlite </label>
                                            <input type="text" class="form-control" id="field-id_nationlite" aria-describedby="field-id_nationlite" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-lieu_naissance"> lieu_naissance </label>
                                            <input type="text" class="form-control" id="field-lieu_naissance" aria-describedby="field-lieu_naissance" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-jumeaux"> jumeaux </label>
                                            <input type="text" class="form-control" id="field-jumeaux" aria-describedby="field-jumeaux" placeholder="">
                                        </div>
                                    </div>
                                </form>
                                </hr>
                                <h6 class="mt-1"> <strong>Décès</strong> </h6>
                                <hr />
                                <form class="mt-2">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-jd_deces_h"> jd_deces_h </label>
                                            <input type="text" class="form-control" id="field-jd_deces_h" aria-describedby="field-jd_deces_h" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-md_deces_h"> md_deces_h </label>
                                            <input type="text" class="form-control" id="field-md_deces_h" aria-describedby="field-md_deces_h" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-ad_deces_h"> ad_deces_h </label>
                                            <input type="text" class="form-control" id="field-ad_deces_h" aria-describedby="field-ad_deces_h" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-jd_deces_g"> jd_deces_g </label>
                                            <input type="text" class="form-control" id="field-jd_deces_g" aria-describedby="field-jd_deces_g" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-md_deces_g"> md_deces_g </label>
                                            <input type="text" class="form-control" id="field-md_deces_g" aria-describedby="field-md_deces_g" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-ad_deces_g"> ad_deces_g </label>
                                            <input type="text" class="form-control" id="field-ad_deces_g" aria-describedby="field-ad_deces_g" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-heure_deces"> heure_deces </label>
                                            <input type="text" class="form-control" id="field-heure_deces" aria-describedby="field-heure_deces" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-minute_deces"> minute_deces </label>
                                            <input type="text" class="form-control" id="field-minute_deces" aria-describedby="field-minute_deces" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-lieu_deces"> lieu_deces </label>
                                            <input type="text" class="form-control" id="field-lieu_deces" aria-describedby="field-lieu_deces" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-lieuresidence"> lieuresidence </label>
                                            <input type="text" class="form-control" id="field-lieuresidence" aria-describedby="field-lieuresidence" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-lieu_residence_pere_ar"> lieu_residence_pere_ar </label>
                                            <input type="text" class="form-control" id="field-lieu_residence_pere_ar" aria-describedby="field-lieu_residence_pere_ar" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-lieu_residence_mere_ar"> lieu_residence_mere_ar </label>
                                            <input type="text" class="form-control" id="field-lieu_residence_mere_ar" aria-describedby="field-lieu_residence_mere_ar" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-id_profession"> id_profession </label>
                                            <input type="text" class="form-control" id="field-id_profession" aria-describedby="field-id_profession" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-statutfamilialle"> statutfamilialle </label>
                                            <input type="text" class="form-control" id="field-statutfamilialle" aria-describedby="field-statutfamilialle" placeholder="">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="Pere">
                                <form class="mt-2">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-decede_pere"> decede_pere </label>
                                            <input type="text" class="form-control" id="field-decede_pere" aria-describedby="field-decede_pere" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-prenom_pere_ar"> prenom_pere_ar </label>
                                            <input type="text" class="form-control" id="field-prenom_pere_ar" aria-describedby="field-prenom_pere_ar" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-prenom_pere_fr"> prenom_pere_fr </label>
                                            <input type="text" class="form-control" id="field-prenom_pere_fr" aria-describedby="field-prenom_pere_fr" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-ascendant_pere_ar"> ascendant_pere_ar </label>
                                            <input type="text" class="form-control" id="field-ascendant_pere_ar" aria-describedby="field-ascendant_pere_ar" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-ascendant_pere_fr"> ascendant_pere_fr </label>
                                            <input type="text" class="form-control" id="field-ascendant_pere_fr" aria-describedby="field-ascendant_pere_fr" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-id_nationalite_pere"> id_nationalite_pere </label>
                                            <input type="text" class="form-control" id="field-id_nationalite_pere" aria-describedby="field-id_nationalite_pere" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-id_profession_pere"> id_profession_pere </label>
                                            <input type="text" class="form-control" id="field-id_profession_pere" aria-describedby="field-id_profession_pere" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-jd_naissance_pere_h"> jd_naissance_pere_h </label>
                                            <input type="text" class="form-control" id="field-jd_naissance_pere_h" aria-describedby="field-jd_naissance_pere_h" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-md_naissance_pere_h"> md_naissance_pere_h </label>
                                            <input type="text" class="form-control" id="field-md_naissance_pere_h" aria-describedby="field-md_naissance_pere_h" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-ad_naissance_pere_h"> ad_naissance_pere_h </label>
                                            <input type="text" class="form-control" id="field-ad_naissance_pere_h" aria-describedby="field-ad_naissance_pere_h" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-jd_naissance_pere_g"> jd_naissance_pere_g </label>
                                            <input type="text" class="form-control" id="field-jd_naissance_pere_g" aria-describedby="field-jd_naissance_pere_g" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-md_naissance_pere_g"> md_naissance_pere_g </label>
                                            <input type="text" class="form-control" id="field-md_naissance_pere_g" aria-describedby="field-md_naissance_pere_g" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-ad_naissance_pere_g"> ad_naissance_pere_g </label>
                                            <input type="text" class="form-control" id="field-ad_naissance_pere_g" aria-describedby="field-ad_naissance_pere_g" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-lieu_naissance_pere"> lieu_naissance_pere </label>
                                            <input type="text" class="form-control" id="field-lieu_naissance_pere" aria-describedby="field-lieu_naissance_pere" placeholder="">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="Mere">
                                <form class="mt-2">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-decede_pere"> decede_pere </label>
                                            <input type="text" class="form-control" id="field-decede_pere" aria-describedby="field-decede_pere" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-prenom_mere_ar"> prenom_mere_ar </label>
                                            <input type="text" class="form-control" id="field-prenom_mere_ar" aria-describedby="field-prenom_mere_ar" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-prenom_mere_fr"> prenom_mere_fr </label>
                                            <input type="text" class="form-control" id="field-prenom_mere_fr" aria-describedby="field-prenom_mere_fr" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-ascendant_mere_ar"> ascendant_mere_ar </label>
                                            <input type="text" class="form-control" id="field-ascendant_mere_ar" aria-describedby="field-id_type_acte" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-ascendant_mere_fr"> ascendant_mere_fr </label>
                                            <input type="text" class="form-control" id="field-ascendant_mere_fr" aria-describedby="field-ascendant_mere_fr" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-id_nationalite_mere"> id_nationalite_mere </label>
                                            <input type="text" class="form-control" id="field-id_nationalite_mere" aria-describedby="field-id_nationalite_mere" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-id_profession_mere"> id_profession_mere </label>
                                            <input type="text" class="form-control" id="field-id_profession_mere" aria-describedby="field-id_profession_mere" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-jd_naissance_mere_h"> jd_naissance_mere_h </label>
                                            <input type="text" class="form-control" id="field-jd_naissance_mere_h" aria-describedby="field-jd_naissance_mere_h" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-md_naissance_mere_h"> md_naissance_mere_h </label>
                                            <input type="text" class="form-control" id="field-md_naissance_mere_h" aria-describedby="field-md_naissance_mere_h" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-ad_naissance_mere_h"> ad_naissance_mere_h </label>
                                            <input type="text" class="form-control" id="field-ad_naissance_mere_h" aria-describedby="field-ad_naissance_mere_h" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-jd_naissance_mere_g"> jd_naissance_mere_g </label>
                                            <input type="text" class="form-control" id="field-jd_naissance_mere_g" aria-describedby="field-jd_naissance_mere_g" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-md_naissance_mere_g"> md_naissance_mere_g </label>
                                            <input type="text" class="form-control" id="field-md_naissance_mere_g" aria-describedby="field-md_naissance_mere_g" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-ad_naissance_mere_g"> ad_naissance_mere_g </label>
                                            <input type="text" class="form-control" id="field-ad_naissance_mere_g" aria-describedby="field-ad_naissance_mere_g" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-lieu_naissance_mere"> lieu_naissance_mere </label>
                                            <input type="text" class="form-control" id="field-lieu_naissance_mere" aria-describedby="field-lieu_naissance_mere" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-adresse_residence_parents"> adresse_residence_parents </label>
                                            <input type="text" class="form-control" id="field-adresse_residence_parents" aria-describedby="field-adresse_residence_parents" placeholder="">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="Declaration">
                                <h6 class="mt-2"> <strong>Acte</strong> </h6>
                                <hr />
                                <form class="mt-2">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-jd_etabli_acte_h"> jd_etabli_acte_h </label>
                                            <input type="text" class="form-control" id="field-jd_etabli_acte_h" aria-describedby="field-jd_etabli_acte_h" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-ad_etabli_acte_h"> ad_etabli_acte_h </label>
                                            <input type="text" class="form-control" id="field-ad_etabli_acte_h" aria-describedby="field-ad_etabli_acte_h" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-md_etabli_acte_h"> md_etabli_acte_h </label>
                                            <input type="text" class="form-control" id="field-md_etabli_acte_h" aria-describedby="field-md_etabli_acte_h" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-jd_etabli_acte_g"> jd_etabli_acte_g </label>
                                            <input type="text" class="form-control" id="field-jd_etabli_acte_g" aria-describedby="field-jd_etabli_acte_g" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-ad_etabli_acte_g"> ad_etabli_acte_g </label>
                                            <input type="text" class="form-control" id="field-ad_etabli_acte_g" aria-describedby="field-ad_etabli_acte_g" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-md_etabli_acte_g"> md_etabli_acte_g </label>
                                            <input type="text" class="form-control" id="field-md_etabli_acte_g" aria-describedby="field-md_etabli_acte_g" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-id_officier"> id_officier </label>
                                            <input type="text" class="form-control" id="field-id_officier" aria-describedby="field-id_officier" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-sign_officier"> sign_officier </label>
                                            <input type="text" class="form-control" id="field-sign_officier" aria-describedby="field-sign_officier" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-sceau_officier"> sceau_officier </label>
                                            <input type="text" class="form-control" id="field-sceau_officier" aria-describedby="field-sceau_officier" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-heure_etabli_acte"> heure_etabli_acte </label>
                                            <input type="text" class="form-control" id="field-heure_etabli_acte" aria-describedby="field-heure_etabli_acte" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-minute_etabli_acte"> minute_etabli_acte </label>
                                            <input type="text" class="form-control" id="field-minute_etabli_acte" aria-describedby="field-minute_etabli_acte" placeholder="">
                                        </div>
                                    </div>
                                </form>
                                <h6 class="mt-2"> <strong>Jugement</strong> </h6>
                                <hr />
                                <form class="mt-2">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-id_tribunal"> id_tribunal </label>
                                            <input type="text" class="form-control" id="field-id_tribunal" aria-describedby="field-id_tribunal" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-num_jugement"> num_jugement </label>
                                            <input type="text" class="form-control" id="field-num_jugement" aria-describedby="field-num_jugement" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-num_dossier"> num_dossier </label>
                                            <input type="text" class="form-control" id="field-num_dossier" aria-describedby="field-num_dossier" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-annee_dossier"> annee_dossier </label>
                                            <input type="text" class="form-control" id="field-annee_dossier" aria-describedby="field-annee_dossier" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-jd_etablissement_jugement_h"> jd_etablissement_jugement_h </label>
                                            <input type="text" class="form-control" id="field-jd_etablissement_jugement_h" aria-describedby="field-jd_etablissement_jugement_h" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-md_etablissement_jugement_h"> md_etablissement_jugement_h </label>
                                            <input type="text" class="form-control" id="field-md_etablissement_jugement_h" aria-describedby="field-md_etablissement_jugement_h" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-ad_etablissement_jugement_h"> ad_etablissement_jugement_h </label>
                                            <input type="text" class="form-control" id="field-ad_etablissement_jugement_h" aria-describedby="field-ad_etablissement_jugement_h" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-jd_etablissement_jugement_g"> jd_etablissement_jugement_g </label>
                                            <input type="text" class="form-control" id="field-jd_etablissement_jugement_g" aria-describedby="field-jd_etablissement_jugement_g" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-md_etablissement_jugement_g"> md_etablissement_jugement_g </label>
                                            <input type="text" class="form-control" id="field-md_etablissement_jugement_g" aria-describedby="field-md_etablissement_jugement_g" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-ad_etablissement_jugement_g"> ad_etablissement_jugement_g </label>
                                            <input type="text" class="form-control" id="field-ad_etablissement_jugement_g" aria-describedby="field-ad_etablissement_jugement_g" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-j_prononciation_jugement_g"> j_prononciation_jugement_g </label>
                                            <input type="text" class="form-control" id="field-j_prononciation_jugement_g" aria-describedby="field-j_prononciation_jugement_g" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-md_prononciation_jugement_g"> md_prononciation_jugement_g </label>
                                            <input type="text" class="form-control" id="field-md_prononciation_jugement_g" aria-describedby="field-md_prononciation_jugement_g" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-ad_prononciation_jugement_g"> ad_prononciation_jugement_g </label>
                                            <input type="text" class="form-control" id="field-ad_prononciation_jugement_g" aria-describedby="field-ad_prononciation_jugement_g" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-j_prononciation_jugement_h"> j_prononciation_jugement_h </label>
                                            <input type="text" class="form-control" id="field-j_prononciation_jugement_h" aria-describedby="field-j_prononciation_jugement_h" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-md_prononciation_jugement_h"> md_prononciation_jugement_h </label>
                                            <input type="text" class="form-control" id="field-md_prononciation_jugement_h" aria-describedby="field-md_prononciation_jugement_h" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-ad_prononciation_jugement_h"> ad_prononciation_jugement_h </label>
                                            <input type="text" class="form-control" id="field-ad_prononciation_jugement_h" aria-describedby="field-ad_prononciation_jugement_h" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-j_reception_jugement_g"> j_reception_jugement_g </label>
                                            <input type="text" class="form-control" id="field-j_reception_jugement_g" aria-describedby="field-j_reception_jugement_g" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-md_reception_jugement_g"> md_reception_jugement_g </label>
                                            <input type="text" class="form-control" id="field-md_reception_jugement_g" aria-describedby="field-md_reception_jugement_g" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-ad_reception_jugement_g"> ad_reception_jugement_g </label>
                                            <input type="text" class="form-control" id="field-ad_reception_jugement_g" aria-describedby="field-ad_reception_jugement_g" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-j_reception_jugement_h"> j_reception_jugement_h </label>
                                            <input type="text" class="form-control" id="field-j_reception_jugement_h" aria-describedby="field-j_reception_jugement_h" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="field-md_reception_jugement_h"> md_reception_jugement_h </label>
                                            <input type="text" class="form-control" id="field-md_reception_jugement_h" aria-describedby="field-md_reception_jugement_h" placeholder="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="field-ad_reception_jugement_h"> ad_reception_jugement_h </label>
                                            <input type="text" class="form-control" id="field-ad_reception_jugement_h" aria-describedby="field-ad_reception_jugement_h" placeholder="">
                                        </div>
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
                <button type="button" class="btn btn-success form-correct-btn"> Déjà Correct <i class="fas fa-check-double"></i></button>
                <button type="button" class="btn btn-primary form-update-save" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;"> Enregistrer <i class="far fa-save ml-1"></i></button>
            </div>
        </div>
    </div>
</div>