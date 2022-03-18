    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion <?= isset($_SESSION['SideBar']) ? $_SESSION['SideBar'] : "" ?>" id="accordionSidebar" 
        style="background: black;">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-chart-area"></i>
        </div>
        <div class="sidebar-brand-text mx-3">ETAT - CIVIL</div>
      </a>      

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Heading -->
      <div class="sidebar-heading">
         LISTE ETATS
      </div>

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item linkSideBar" id="etatActe">
        <a id="btnListEtat" class="nav-link collapsed" href="form_acte_saisi.php" >
          <i class="fas fa-angle-double-right"></i>
          <span> ACTES </span>
        </a>
      </li> 

      <li class="nav-item linkSideBar" id="etatMention">
        <a id="btnListEtat" class="nav-link collapsed" href="form_calcul_mention.php" >
          <i class="fas fa-angle-double-right"></i>
          <span> MENTION </span>
        </a>
      </li> 

      <li class="nav-item linkSideBar" id="etatControle1">
        <a id="btnListEtat" class="nav-link collapsed" href="form_controle_1.php" >
          <i class="fas fa-angle-double-right"></i>
          <span> CONTROLE 1 </span>
        </a>
      </li> 

      <li class="nav-item linkSideBar" id="etatControle2">
        <a id="btnListEtat" class="nav-link collapsed" href="form_controle_2.php" >
          <i class="fas fa-angle-double-right"></i>
          <span> CONTROLE 2 </span>
        </a>
      </li> 


        <!-- <div id="scanLink" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header"> OPTION :</h6>            
            <hr style="margin: 1;"/>
            <a class="collapse-item bg-dark text-white" href="form_acte_saisi.php">  Saisie Actes </a>
            <a class="collapse-item bg-dark text-white mt-2" href="form_calcul_mention.php"> Calcul Mention </a>
            <a class="collapse-item bg-dark text-white mt-2" href="form_controle_1.php">  Contrôle Phase 1 </a>
            <a class="collapse-item bg-dark text-white mt-2" href="form_controle_2.php">  Contrôle Phase 2 </a>
          </div>
        </div> -->
      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>
    </ul>
    <!-- End of Sidebar -->