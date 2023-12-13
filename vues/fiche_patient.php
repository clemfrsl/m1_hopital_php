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

    // Récupération de la photo associé dans la base de données
    $requete_photo = "SELECT URLMedia FROM Media WHERE CodePatients = '".$instances2[$lienClique][0]."' and TypeMedia = 'photo'  and (SELECT MAX(DateEnregistrement) FROM Media WHERE CodePatients = '".$instances2[$lienClique][0]."' and TypeMedia = 'photo') = DateEnregistrement";
    $result_photo = mysqli_query($connexion, $requete_photo);
    $row_photo = mysqli_fetch_assoc($result_photo);
    if ($row_photo != null){
        $photo = "'data/". $row_photo['URLMedia']."'";
    }
    else{
        $photo = null;
    }

    // Récupération de la prescription associé dans la base de données
    $requete_prescription = "SELECT URLMedia FROM Media WHERE CodePatients = '".$instances2[$lienClique][0]."' and TypeMedia = 'prescription'  and (SELECT MAX(DateEnregistrement) FROM Media WHERE CodePatients = '".$instances2[$lienClique][0]."' and TypeMedia = 'prescription') = DateEnregistrement";
    $result_prescription = mysqli_query($connexion, $requete_prescription);
    $row_prescription = mysqli_fetch_assoc($result_prescription);
    if ($row_prescription != null){
        $prescription = "data/". $row_prescription['URLMedia'].".pdf";
    }
    else{
        $prescription = null;
    }

    ?>

    <div class="container row">
        <div class="col-md-6 card text-bg-secondary mb-3">
            <br>
                <div class="md-3">
                
                    <div class="col-10">
                        <?php 
                        // Affichage des détails du patient
                        if($photo != null){
                            echo "<img src=".$photo." alt='image'>";
                        } 
                        echo '<h2>' . $instances2[$lienClique][1] . " " . $instances2[$lienClique][2]."</h2>" ; 
                        echo '<p> Sexe : ' . $sexe ;
                        echo '<p> Date de naissance : ' . date("d-m-Y", strtotime($instances2[$lienClique][4])) ;
                        echo '<p> Pays de naissance : ' . $instances2[$lienClique][12] ;
                        if ($instances2[$lienClique][5] != ""){
                            echo '<p> Numero sécurité social : ' . $instances2[$lienClique][5] ;
                        }
                        echo '<p> Date entrée : ' . date("d-m-Y", strtotime($instances2[$lienClique][7])) ;

                        echo '<p> Motif de visite : ' . $instances2[$lienClique][10] ;
                        ?>
                        </br></br>
                        <a href="index.php" class="btn btn-success" title="Retour"> Retour </a>
                        <?php
                            if ($prescription != null) {
                                echo '<a href="#" class="btn btn-primary" id="dernierePrescription">Dernière Prescription</a>';
                                echo '<script>';
                                echo 'var prescription = "' . $prescription . '";';
                                echo '</script>';
                            }
                            ?>
                            <script>
                            document.getElementById('dernierePrescription').addEventListener('click', function() {
                                window.open(prescription);
                            });
                            </script>
                        
                    </div>
                </div>
        </div>
    </div>
</main>
