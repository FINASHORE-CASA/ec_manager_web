<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

  <!-- Sidebar Toggle (Topbar) -->
  <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
    <i class="fa fa-bars"></i>
  </button>

  <!-- Topbar Navbar -->
  <ul class="navbar-nav ml-auto">

    <?= 
      ($bdd_status == "undefined") 
      ? '<li class="nav-item">
          <a id="bd_name_link" class="nav-link" href="gestion_db_setting.php">
            <span class="badge badge-danger">
              Aucune Base de données Sélectionnée
            </span>
          </a>
        </li>' 
        : 
        '<li class="nav-item">
          <a id="bd_name_link" class="nav-link" href="gestion_db_setting.php">
            <i class="fa fa-database" aria-hidden="true"></i>
            <span class="badge badge-default">
              '.$bdd_status.'
            </span>
          </a>
        </li>'
    ?>  

    <!-- Nav Item - User Information -->
    <li class="nav-item dropdown no-arrow">
      <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="mr-2 d-none d-lg-inline text-gray-600 small"> 
          <?=isset($_SESSION['user']->login) ? $_SESSION['user']->login : ""?>                   
        </span>
        <img class="img-profile rounded-circle" 
          src="https://images.unsplash.com/photo-1602202655232-5c3c41d3100f?crop=entropy&cs=tinysrgb&fit=crop&fm=jpg">
      </a>

      <!-- Dropdown - User Information -->
      <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
          <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
          Déconnexion
        </a>
      </div>
    </li>

  </ul>

</nav>
<!-- End of Topbar -->