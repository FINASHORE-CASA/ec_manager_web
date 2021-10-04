   <!-- Formulaire de génération des Etats en PDF-->
   <div class="modal fade" id="EtatModalPdf" 
      tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
    <form method="POST" action="proccess/pdf_generator/generer_etat_registre.php">
      <div class="modal-content">
        <div class="modal-header" style="background: #4e73df;color:#ffffff;">
          <h5 class="modal-title" id="exampleModalLabel"> Formulaire Etat </h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Formulaire de configuration de l'etats qui sera generer -->
            <div class="form-group">
              <label for="exampleFormControlSelect1">Statut Registre</label>
              <select class="form-control" id="exampleFormControlSelect1" name="statut_registre">
                <option value="">TOUS</option>
                <option value="3">INDEXE</option>
                <option value="2">SCANNE</option>
                <option value="1">PREINDEXE</option>
                <option value="0">CREE/INVENTAIRE</option>
              </select>
            </div>
            <div class="form-group">
              <label for="exampleFormControlSelect1">Type Registre</label>
              <select class="form-control" id="exampleFormControlSelect1" name="type_registre">
                <option value="">TOUS</option>
                <option value="R3">R3</option>
                <option value="R4">R4</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="reset" data-dismiss="modal">Annuler</button>
            <input class="btn btn-danger" href="#" type="submit" value="Generer PDF "/>
            <!-- <input class="btn btn-success" href="#" type="submit" value="Generer XLSX "/> -->
          </div>
        </div>
      </div>
    </form>        
  </div>


   <!-- Formulaire de génération des Etats en XLSX-->
   <div class="modal fade" id="EtatModalXlsx" 
      tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
    <form method="POST" action="proccess/xlsx_generator/generer_etat_registre.php">
      <div class="modal-content">
        <div class="modal-header" style="background: #4e73df;color:#ffffff;">
          <h5 class="modal-title" id="exampleModalLabel"> Formulaire Etat </h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div> 
        <div class="modal-body">
          <!-- Formulaire de configuration de l'etats qui sera generer -->
            <div class="form-group">
              <label for="exampleFormControlSelect1">Statut Registre</label>
              <select class="form-control" id="exampleFormControlSelect1" name="statut_registre">
                <option value="">TOUS</option>
                <option value="3">INDEXE</option>
                <option value="2">SCANNE</option>
                <option value="1">PREINDEXE</option>
                <option value="0">CREE/INVENTAIRE</option>
              </select>
            </div>
            <div class="form-group">
              <label for="exampleFormControlSelect1">Type Registre</label>
              <select class="form-control" id="exampleFormControlSelect1" name="type_registre">
                <option value="">TOUS</option>
                <option value="R3">R3</option>
                <option value="R4">R4</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="reset" data-dismiss="modal">Annuler</button>
            <input class="btn btn-success" href="#" type="submit" value="Generer XLSX "/>
            <!-- <input class="btn btn-success" href="#" type="submit" value="Generer XLSX "/> -->
          </div>
        </div>
      </div>
    </form>        
  </div>

