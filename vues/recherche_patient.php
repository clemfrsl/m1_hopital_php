<!-- HÔPITAL.PHP NOV 2023 -->
<!-- CLÉMENT FRESNEL -->
<!-- ZOÉ MARTINEZ -->
<?php

    $connexion = getConnexionBD();
    $message = " ";

    // Initialisation des variables de session
    $_SESSION['nom'] = '';
    $_SESSION['motif'] = '';
    $_SESSION['pays'] = '';
    $_SESSION['dateMin'] = '';
    $_SESSION['dateMax'] = '';
    $_SESSION['message'] = '';
    
    // Récupération des motifs et des pays depuis la base de données
    $motifs = getInstances($connexion, "Motifs", "Libellé");
    $pays = getInstances($connexion, "Pays", "Libellé");
    

    //vérification soumission du formulaire
    if(isset($_POST['submit']))
    {
        // Assignation des valeurs du formulaire aux variables de session
        $_SESSION['nom'] = $_POST['nom'];

        if($_POST['choixMotifs'] == 'i') {
            $_SESSION['motif'] = "";
        }
        else {
            $_SESSION['motif'] = $_POST['choixMotifs'];
        }
        
        if($_POST['choixPays'] == 'i') {
            $_SESSION['pays'] = "";
        }
        else {
            $_SESSION['pays'] = $_POST['choixPays'];
        }
        
        if($_POST['minDate'] == 'i') {
            $_SESSION['dateMin'] = "";
        }
        else {
            $_SESSION['dateMin'] = $_POST['minDate'];
        }
        
        if($_POST['maxDate'] == 'i') {
            $_SESSION['dateMax'] = "";
        }
        else {
            $_SESSION['dateMax'] = $_POST['maxDate'];
        }
        
        $instances = getInstancesAffichage($connexion, $_SESSION['nom'], $_SESSION['motif'], $_SESSION['pays'], $_SESSION['dateMin'], $_SESSION['dateMax']);
        $_SESSION['instances'] = $instances;
        
        
        
    }
       
?>


<main>
    <div class="container row">
        <div class="col-md-6 card text-bg-secondary mb-3">
            </br>
            <form action="#" method="post">
                <!-- Formulaire de recherche -->
                <div class="md-3">
                    <!-- Input pour le nom du patient -->
                     <label class="form-label"> Nom : </label>
                    <div class="col-10">  
                        <?php
                            // Affiche la valeur existante si elle existe sinon met la valeur par default
                            if(!is_null($_SESSION['nom'])) {
                                echo "<input class='form-select' name='nom' value = '".$_SESSION['nom']."'>";
                            }
                            else {
                                echo "<input class='form-select' name='nom'>";
                            }
                        ?>
                    </div>
                </div>   

                </br>

                <div class="md-3"> 
                    <!-- Input pour le motif de visite du patient -->
                    <div class="col-10">
                        <label class="form-label">  Motif de visite à l'hôpital : </label>
                        <select name="choixMotifs" class="form-select">
                            <?php
                            // Affiche la valeur existante si elle existe sinon met la valeur par default
                            if($_SESSION['motif'] != "") {
                                echo "<option value='".$_SESSION['motif']."'>".$_SESSION['motif']."</option>";
                            }
                            
                            echo "<option value='i'>Indifférent</option>";
                            
                            foreach ($motifs as $motif) {
                                if($_SESSION['motif'] != $motif) {
                                    echo "<option value='".$motif[0]."'>".$motif[0]."</option>";
                                }
                                
                            }
                            ?>
                        </select> 
                    </div>
                </div>

                </br>

                <div class="md-3">
                    <!-- Input pour la nationalité du patient -->
                    <div class="col-10">
                        <label class="form-label"> Nationalité : </label>
                        <select name="choixPays" class="form-select">
                            <?php
                            // Affiche la valeur existante si elle existe sinon met la valeur par default
                            if($_SESSION['pays'] != "") {
                                echo "<option value='".$_SESSION['pays']."'>".$_SESSION['pays']."</option>";
                            }
                            
                            echo "<option value = 'i'> Indifférent </option>";
                            
                            foreach ($pays as $localité) {
                                echo "<option value = '".$localité[0]."'>".$localité[0]."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>   

                </br>

                <label class="form-label"> Intervalle années de naissance : </label>
                <div class="row">
                    <!-- Input pour la date de naissance minimum du patient -->
                    <div class="col-md-5">
                        <select name="minDate" class="form-select">
                            <?php
                            // Affiche la valeur existante si elle existe sinon met la valeur par default
                                if($_SESSION['dateMin'] != NULL) {
                                    echo "<option value='".$_SESSION['dateMin']."'>".$_SESSION['dateMin']."</option>";
                                }
                                echo "<option value='i'>Indifférent</option>";
                            
                                for ($i=date("Y"); $i>1919; $i--) {
                                    echo "<option value = '".$i."'>".$i."</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <!-- Input pour la date de naissance maximum du patient -->
                        <select name="maxDate" class="form-select">
                            <?php
                            // Affiche la valeur existante si elle existe sinon met la valeur par default
                                if($_SESSION['dateMax'] != NULL) {
                                    echo "<option value='".$_SESSION['dateMax']."'>".$_SESSION['dateMax']."</option>";
                                }
                                echo "<option value='i'>Indifférent</option>";
                                
                                for ($i=date("Y"); $i>1919; $i--) {
                                    echo "<option value = '".$i."'>".$i."</option>";
                                }
                            ?>
                        </select>

                    </div>  
                </div>
                
                </br>
                <div class="md-3 text-center">
                    <button type="submit" name="submit" class="btn btn-success">Rechercher</button>
                    <script>
                        function redirectToIndex() {
                            window.location.href = 'index.php';
                        }
                    </script>
                    
                    <button type="reset" class="btn btn-danger" onclick="redirectToIndex()">Réinitialiser</button>
                </div>

            </form>
        </br>
    </div>

    <div class="col-md-6">
            <div class="col-md-4">
                <!-- Affichage des résultats -->
                    <?php
                        if(isset($_POST['submit']))
                        {
                            if(empty($instances)) {
                                echo '<div class="card bg-secondary text-white" style="width: 18rem;">';
                                echo '<div class="card-body">';
                                echo "Il n'y a pas de résultats avec vos critères de recherche";
                                echo '</div>';
                                echo '</div>';
                                
                            }
                            $i = 0;
                            foreach ($instances as $instance) {
                                // Boucle pour afficher les résultats sous forme de cartes qui contiennent le lien vers fiche_patient
                                echo '<div class="col-sm-6 mb-3 mb-sm-0">';
                                echo '<div class="card bg-secondary" style="width: 18rem;">';
                                echo '<div class="card-body">';
                                echo "<a class='link-light link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover' href='index.php?page=afficher&lien=".$i."'>".$instance[2]." ".$instance[1]."</a>";
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                                $i++;
                            }
                        }
                                            
                    ?>

            </div>
        </div>
    </div>



    
</main>

