    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion <?= isset($_SESSION['SideBar']) ? $_SESSION['SideBar'] : "" ?>" id="accordionSidebar" 
        style="background: black;">

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

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item linkSideBar" id="Saisie">
        <a id="btnListEtat" class="nav-link collapsed" href="form_acte_saisi.php" data-toggle="collapse" data-target="#saisieLink" aria-expanded="true" aria-controls="collapsePages">
          <i class="far fa-edit"></i>
          <span> SAISIE </span>
        </a>
        <div id="saisieLink" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header"> OPTION :</h6>
            <a class="collapse-item" href="saisie_controle_acte_lot.php"> 1 - Contrôle Acte </a>
            <a class="collapse-item" href="initialisation_lot.php"> 2 - Initialisation d'un Lot </a>
            <a class="collapse-item" href="validation_lot.php"> 3 - Validation Lot  </a>
          </div>
        </div>
      </li>
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>
    </ul>
    <!-- End of Sidebar -->