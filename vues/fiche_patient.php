<!-- HÔPITAL.PHP NOV 2023 -->
<!-- CLÉMENT FRESNEL -->
<!-- ZOÉ MARTINEZ -->

<main>
    <?php
    $connexion = getConnexionBD();
    $message = " ";
    $lienClique = null;
    //vérification soumission du formulaire 
    $lienClique = $_GET['lien'];
    $instances2 = $_SESSION['instances'];

    // Récupération de l'information sur le sexe version libellé depuis la base de données
    $requete_sexe = "SELECT libellé FROM Sexe WHERE CodeSexe = '".$instances2[$lienClique][3]."'";
    $result_sexe = mysqli_query($connexion, $requete_sexe);
    $row_sexe = mysqli_fetch_assoc($result_sexe);
    $sexe = $row_sexe['libellé'];
    ?>

    <div class="container row">
        <div class="col-md-6 card text-bg-secondary mb-3">
            <br>
                <div class="md-3">
                
                    <div class="col-10">
                        <?php 
                        // Affichage des détails du patient
                        echo '<h2>' . $instances2[$lienClique][1] . " " . $instances2[$lienClique][2]."</h2>" ; 
                        echo '<p> Sexe : ' . $sexe ;
                        echo '<p> Date de naissance : ' . $instances2[$lienClique][4] ;
                        echo '<p> Pays de naissance : ' . $instances2[$lienClique][12] ;
                        if ($instances2[$lienClique][5] != ""){
                            echo '<p> Numero sécurité social : ' . $instances2[$lienClique][5] ;
                        }
                        echo '<p> Date entrée : ' . $instances2[$lienClique][7] ;

                        echo '<p> Motif de visite : ' . $instances2[$lienClique][10] ;
                        ?>
                        </br></br>
                        <a href="index.php" class="btn btn-success" title="Retour"> Retour </a>
                    </div>
                </div>
        </div>
    </div>
</main>
