    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion <?= isset($_SESSION['SideBar']) ? $_SESSION['SideBar'] : "toggled" ?>" id="accordionSidebar" style="background: <?= isset($main_app_color) ? $main_app_color : "#3b2106"; ?>;">

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
      <?php
      $new = array_filter($modules_saisie, function ($e, $k) use ($list_roles) {
        return array_search($k, $list_roles);
      }, ARRAY_FILTER_USE_BOTH);

      if (count($new) > 0) {
        echo '<li class="nav-item linkSideBar" id="Saisie">
        <a class="nav-link collapsed" href="form_acte_saisi.php" data-toggle="collapse" data-target="#saisieLink" aria-expanded="true" aria-controls="collapsePages">
          <i class="far fa-edit"></i>
          <span> SAISIE </span>
        </a>
        <div id="saisieLink" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header"> OPTION :</h6>';

        foreach ($new as $key => $value) {
          echo '<a class="collapse-item" href="' . $key . '.php">' . $value . '</a>';
        }
        echo '</div></div></li>';
      }
      ?>

      <!-- Nav Item - ACTION MI -->
      <?php
      $new = array_filter($modules_action_IEC, function ($e, $k) use ($list_roles) {
        return array_search($k, $list_roles);
      }, ARRAY_FILTER_USE_BOTH);

      if (count($new) > 0) {
        echo '<li class="nav-item linkSideBar" id="ActionIEC">
        <a class="nav-link collapsed" href="form_acte_saisi.php" data-toggle="collapse" data-target="#ActionIECLink" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-clipboard-check"></i>
          <span> ACTION IEC </span>
        </a>
        <div id="ActionIECLink" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header"> OPTION :</h6>';

        foreach ($new as $key => $value) {
          echo '<a class="collapse-item" href="' . $key . '.php">' . $value . '</a>';
        }
        echo '</div></div></li>';
      }
      ?>

      <!-- Nav Item - ACTION OEC-POPF -->
      <?php
      $new = array_filter($modules_action_OEC_POPF, function ($e, $k) use ($list_roles) {
        return array_search($k, $list_roles);
      }, ARRAY_FILTER_USE_BOTH);

      if (count($new) > 0) {
        echo '<li class="nav-item linkSideBar" id="ActionOEC-POPF">
        <a class="nav-link collapsed" href="form_acte_saisi.php" data-toggle="collapse" data-target="#ActionOEC-POPFLink" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-clipboard-check"></i>
          <span> ACTION OEC-POPF </span>
        </a>
        <div id="ActionOEC-POPFLink" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header"> OPTION :</h6>';

        foreach ($new as $key => $value) {
          echo '<a class="collapse-item" href="' . $key . '.php">' . $value . '</a>';
        }
        echo '</div></div></li>';
      }
      ?>

      <!-- Nav Item - AUDIT  -->
      <?php
      $new = array_filter($modules_audits, function ($e, $k) use ($list_roles) {
        return array_search($k, $list_roles);
      }, ARRAY_FILTER_USE_BOTH);

      if (count($new) > 0) {
        echo '<li class="nav-item linkSideBar" id="Audit">
        <a class="nav-link collapsed" href="form_acte_saisi.php" data-toggle="collapse" data-target="#AuditLink" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-flag-checkered"></i>
          <span> AUDIT </span>
        </a>
        <div id="AuditLink" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header"> OPTION :</h6>';

        foreach ($new as $key => $value) {
          echo '<a class="collapse-item" href="' . $key . '.php">' . $value . '</a>';
        }
        echo '</div></div></li>';
      }
      ?>

      <!-- Nav Item - LIVRAISON -->
      <?php
      $new = array_filter($modules_livraison, function ($e, $k) use ($list_roles) {
        return array_search($k, $list_roles);
      }, ARRAY_FILTER_USE_BOTH);

      if (count($new) > 0) {
        echo '<li class="nav-item linkSideBar" id="Livraison">
        <a class="nav-link collapsed" href="form_acte_saisi.php" data-toggle="collapse" data-target="#LivraisonLink" aria-expanded="true" aria-controls="collapsePages">
          <i class="far fa-share-square"></i>
          <span> LIVRAISON </span>
        </a>
        <div id="LivraisonLink" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header"> OPTION :</h6>';

        foreach ($new as $key => $value) {
          echo '<a class="collapse-item" href="' . $key . '.php">' . $value . '</a>';
        }
        echo '</div></div></li>';
      }
      ?>

      <!-- Nav Item - DEPOT -->
      <?php
      $new = array_filter($modules_depots, function ($e, $k) use ($list_roles) {
        return array_search($k, $list_roles);
      }, ARRAY_FILTER_USE_BOTH);

      if (count($new) > 0) {
        echo '<li class="nav-item linkSideBar" id="Depot">
        <a class="nav-link collapsed" href="form_acte_saisi.php" data-toggle="collapse" data-target="#DepotLink" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-box"></i>          
          <span> DEPÔTS </span>
        </a>
        <div id="DepotLink" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header"> OPTION :</h6>';

        foreach ($new as $key => $value) {
          echo '<a class="collapse-item" href="' . $key . '.php">' . $value . '</a>';
        }
        echo '</div></div></li>';
      }
      ?>
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Nav Item - GESTION STATS  -->
      <?php
      $new = array_filter($modules_gestion_stats, function ($e, $k) use ($list_roles) {
        return array_search($k, $list_roles);
      }, ARRAY_FILTER_USE_BOTH);

      if (count($new) > 0) {
        echo '<li class="nav-item linkSideBar" id="GestionStats">
        <a class="nav-link collapsed" href="form_acte_saisi.php" data-toggle="collapse" data-target="#GestionStatsLink" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-cogs"></i>
          <span> GESTION STATS </span>
        </a>
        <div id="GestionStatsLink" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header"> OPTION :</h6>';

        foreach ($new as $key => $value) {
          echo '<a class="collapse-item" href="' . $key . '.php">' . $value . '</a>';
        }
        echo '</div></div></li>';
      }
      ?>

      <!-- Nav Item - GESTION ECM  -->
      <?php
      $new = array_filter($modules_gestion_ecm, function ($e, $k) use ($list_roles) {
        return array_search($k, $list_roles);
      }, ARRAY_FILTER_USE_BOTH);

      if (count($new) > 0) {
        echo '<li class="nav-item linkSideBar" id="GestionECM">
        <a class="nav-link collapsed" href="form_acte_saisi.php" data-toggle="collapse" data-target="#GestionECMLink" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-cogs"></i>
          <span> GESTION ECM </span>
        </a>
        <div id="GestionECMLink" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header"> OPTION :</h6>';

        foreach ($new as $key => $value) {
          echo '<a class="collapse-item" href="' . $key . '.php">' . $value . '</a>';
        }
        echo '</div></div></li>';
      }
      ?>

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>
    </ul>
    <!-- End of Sidebar -->