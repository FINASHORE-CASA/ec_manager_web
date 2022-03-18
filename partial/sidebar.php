    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion <?= isset($_SESSION['SideBar']) ? $_SESSION['SideBar'] : "toggled" ?>" id="accordionSidebar" 
        style="background: <?=isset($main_app_color) ? $main_app_color : "#3b2106";?>;">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-box-open"></i>
        </div>
        <div class="sidebar-brand-text mx-3">EC-MANAGER</div>
      </a>      

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Heading -->
      <div class="sidebar-heading">
         OPTION
      </div>

      <!-- Nav Item - SAISIE -->
      <li class="nav-item linkSideBar" id="StatsPage">
        <a class="nav-link collapsed" href="stats_page.php">
            <i class="fas fa-chart-area"></i>
            <span> STATS </span>
        </a>
      </li>

      <!-- Nav Item - SAISIE -->
      <li class="nav-item linkSideBar" id="Saisie">
        <a class="nav-link collapsed" href="form_acte_saisi.php" data-toggle="collapse" data-target="#saisieLink" aria-expanded="true" aria-controls="collapsePages">
          <i class="far fa-edit"></i>
          <span> SAISIE </span>
        </a>
        <div id="saisieLink" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header"> OPTION :</h6>
            <a class="collapse-item" href="saisie_controle_acte_lot.php"> 1 - Contrôle Acte </a>
            <a class="collapse-item" href="correction_acte.php"> 2 - Correction Acte </a>
            <a class="collapse-item" href="correction_reqs.php"> 3 - Vigilance  </a>
            <a class="collapse-item" href="initialisation_lot.php"> 4 - Initialisation d'un Lot </a>
            <a class="collapse-item" href="validation_lot.php"> 5 - Validation Lot  </a>
            <?= (isset($_SESSION['user']->type_grant) && ($_SESSION['user']->type_grant == '0' || $_SESSION['user']->type_grant == '2') ) ? '<a class="collapse-item" href="division_lot.php"> 6 - Division Lot </a>' : '' ?> 
          </div>
        </div>
      </li>
      
      <!-- Nav Item - ACTION MI -->
      <li class="nav-item linkSideBar" id="ActionIEC">
        <a class="nav-link collapsed" href="form_acte_saisi.php" data-toggle="collapse" data-target="#ActionIECLink" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-clipboard-check"></i>
          <span> ACTION IEC </span>
        </a>
        <div id="ActionIECLink" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header"> OPTION :</h6>
            <a class="collapse-item" href="saisie_controle_acte_lot_auto.php"> 1 - Contrôle IEC </a>
          </div>
        </div>
      </li>
      
      <!-- Nav Item - ACTION OEC-POPF -->
      <li class="nav-item linkSideBar" id="ActionOEC-POPF">
        <a class="nav-link collapsed" href="form_acte_saisi.php" data-toggle="collapse" data-target="#ActionOEC-POPFLink" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-clipboard-check"></i>
          <span> ACTION OEC-POPF </span>
        </a>
        <div id="ActionOEC-POPFLink" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header"> OPTION :</h6>
            <a class="collapse-item" href="controle_oec_popf.php"> 1 - Contrôle OEC-POPF </a>
          </div>
        </div>
      </li>

      <!-- Nav Item - SAISIE -->
      <?= (isset($_SESSION['user']->type_grant) && $_SESSION['user']->type_grant == '0') ? '
      <li class="nav-item linkSideBar" id="Livraison">
        <a class="nav-link collapsed" href="form_acte_saisi.php" data-toggle="collapse" data-target="#LivraisonLink" aria-expanded="true" aria-controls="collapsePages">
          <i class="far fa-share-square"></i>
          <span> LIVRAISON </span>
        </a>
        <div id="LivraisonLink" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header"> OPTION :</h6>
            <a class="collapse-item" href="purge_lot.php"> 1 - Purge Lot </a>         
            <a class="collapse-item" href="controle_inventaire_liv.php"> 2 - Contrôle Inventaire </a>          
            <a class="collapse-item" href="controle_general_liv.php"> 3 - Contrôle Général </a>    
            <a class="collapse-item" href="transfert_lot.php"> 4 - Transfert Lot </a>       
            <a class="collapse-item" href="split_bd.php"> 5 - Split BD </a>          
          </div>
        </div>
      </li>' : '' ?>
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Nav Item - GESTION ECM  -->      
      <?= (isset($_SESSION['user']->type_grant) && $_SESSION['user']->type_grant == '0') ?
        '<li class="nav-item linkSideBar" id="GestionECM">
          <a class="nav-link collapsed" href="form_acte_saisi.php" data-toggle="collapse" data-target="#GestionECMLink" aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-cogs"></i>
            <span> GESTION ECM </span>
          </a>
          <div id="GestionECMLink" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <h6 class="collapse-header"> OPTION :</h6>
              <a class="collapse-item" href="gestion_users.php"> Gestion utilisateur </a>
              <a class="collapse-item" href="gestion_db_setting.php"> Gestion BD </a>
              <a class="collapse-item" href="gestion_pref_setting.php"> Préférences </a>
              <a class="collapse-item" href="gestion_impotation_setting.php"> Gestion Importation </a>
              <a class="collapse-item" href="script_gestion.php"> Script de gestion </a>
            </div>
          </div>
        </li>' : ''
      ?>

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>
    </ul>
    <!-- End of Sidebar -->