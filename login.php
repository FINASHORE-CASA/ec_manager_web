<?php
  require_once "config/defines.inc.php";

  try 
  {
    // Chargement de la couleur de session
    if(isset($_SESSION["style"]))
    {
      $main_app_color = $_SESSION["style"];
    }
    else
    {
      // Récupération de la couleur			
      $json = json_decode(file_get_contents("config/preferences.json"));

      if(isset($json->color))
      {
        $_SESSION["style"] = $json->color; 
        $main_app_color = $_SESSION["style"];
      }
      else
      {				
        $_SESSION["style"] = "#3b2106"; 
        $json->color = $_SESSION["style"];
        file_put_contents("config/preferences.json",json_encode($json));
      }
    }
    
  } catch (\Throwable $th) {}
?>        

<!DOCTYPE html>
<html lang="fr">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>  <?=$app_name ?> </title>
  <link rel="icon" href="img/favicon.ico" />

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-dark">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">            
      <div class="col-xl-10 col-lg-12 col-md-9">
        
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <!-- se code charge l'image backgound de session qui se trove sur un server unsplashed -->
              <div class="col-lg-6 d-none d-lg-block " style="padding: 70px;padding-left:115px;background: <?=isset($main_app_color) ? $main_app_color : "#b87630";?>;">
                <img src="./img/open-box.png" alt="image home" class="img-responsive" style="height:230px;"/>
              </div>              
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4"> Connectez - Vous ! </h1>
                    <?php
                      if(isset($_GET['log']))
                      {
                        echo "<h6 class=\"text-danger\" id=\"#loginError\">{$_GET['log']}</h6>";
                      }                      
                    ?>
                  </div>
                  <form class="user" action="./proccess/login.php" method="POST">
                    <div class="form-group">
                      <input type="text" 
                             class="form-control form-control-user"  
                             name="login"                            
                             placeholder="Login" required/>
                    </div>
                    <div class="form-group">
                      <input type="password" 
                             class="form-control form-control-user" 
                             name="mot_de_passe"
                             placeholder="Mot de Passe" required/>
                    </div>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="customCheck"
                        name="se_souvenir">
                        <label class="custom-control-label" for="customCheck">Se souvenir de moi</label>
                      </div>
                    </div>
                    <hr>
                    <input type="submit"  class="btn btn-primary btn-user btn-block" style="background:<?=isset($main_app_color) ? $main_app_color : "#b87630";?>;border:none;" value="se connecter"/>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

</body>

</html>