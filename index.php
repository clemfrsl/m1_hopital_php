<?php
// index.php fait office de controleur frontal
session_start(); // démarre ou reprend une session
ini_set('display_errors', 1); // affiche les erreurs (au cas où)
ini_set('display_startup_errors', 1); // affiche les erreurs (au cas où)
error_reporting(E_ALL); // affiche les erreurs (au cas où)
require('vues/ressources_communes.php'); // inclut le fichier ressources_communes
$connexion = getConnexionBD(); // connexion à la BD
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    
    <!-- le titre du document, qui apparait dans l'onglet du navigateur -->
    <title><?= "Hopital_php" ?></title>
    <!-- connection avec boostrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    
</head>
<body>
    <!-- Ajout du header sur le site -->
    <?php include('static/header.php'); ?>

    
    <div id=divCentral>
    <main>
        <!-- Ajout de la page central qui va varié, la page recherche_patient étant la page d'accueil -->
    <?php
        
        $vue = 'recherche_patient';
        
        if(isset($_GET['page'])) {
            $nomPage = $_GET['page'];
            
            if(isset($routes[$nomPage])) { // si la page existe dans le tableau des routes, on la charge
                $vue = $routes[$nomPage]['vue'];
            }
        }
        include('vues/' . $vue . '.php');

        ?>
        
    </main>
    </div>
    <!-- Ajout du footer sur le site -->
    <?php include('static/footer.php'); ?>
</body>
</html>
