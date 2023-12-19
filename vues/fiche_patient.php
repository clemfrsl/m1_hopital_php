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
    $requete_photo = "SELECT URLMedia, Signature FROM Media WHERE CodePatients = '".$instances2[$lienClique][0]."' and TypeMedia = 'photo'  and (SELECT MAX(DateEnregistrement) FROM Media WHERE CodePatients = '".$instances2[$lienClique][0]."' and TypeMedia = 'photo') = DateEnregistrement";
    $result_photo = mysqli_query($connexion, $requete_photo);
    $row_photo = mysqli_fetch_assoc($result_photo);
    if ($row_photo != null){
        $signaturePho = $row_photo['Signature'];
    }
    else{
        $signaturePho = null;
    }
    $contenuPho = glob("data".'/*');
    $photo= trouveNom($contenuPho, $signaturePho);

    // Récupération de la prescription associé dans la base de données
    $requete_prescription = "SELECT URLMedia, Signature FROM Media WHERE CodePatients = '".$instances2[$lienClique][0]."' and TypeMedia = 'prescription'  and (SELECT MAX(DateEnregistrement) FROM Media WHERE CodePatients = '".$instances2[$lienClique][0]."' and TypeMedia = 'prescription') = DateEnregistrement";
    $result_prescription = mysqli_query($connexion, $requete_prescription);
    $row_prescription = mysqli_fetch_assoc($result_prescription);
    if ($row_prescription != null){
        $signaturePres = $row_prescription['Signature'];
    }
    else{
        $signaturePres = null;
    }
    $contenuPres = glob("data" . '/*');
    $prescription = trouveNom($contenuPres, $signaturePres);
        

    ?>

    <div class="container row">
        <!-- Côté gauche de la page-->
        <div class="col-md-4 card text-bg-secondary mb-3">
            <br>
                <div class="md-3">
                
                    <div class="col-10">
                        <?php 
                        // Affichage des détails du patient
                        if($photo != null && file_exists($photo)){
                            if(substr($photo, -4) == ".pdf"){
                                echo '<iframe src="'.$photo.'" width="200" height="280"></iframe>';
                            }
                            else{
                                echo "<img src='".$photo."' alt='image' width='200' height='280'>";
                            }
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

                        <div class="md-4>
                            <form action="index.php" method="post">
                                <button type="button" class="btn btn-danger" onclick="history.back()">Retour</button>
                            </form>
                            <?php
                                // Affiche la dernière prescription sur un nouvel onglet
                                if ($prescription != null) {
                                    echo '<a href="#" class="btn btn-warning text-white" id="dernierePrescription">Dernière Prescription</a>';
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
                        <br>
                        
                    </div>
                </div>
                
        </div>

        <div class="col-md-1 text-bg-transparent mb-3">
        </div>

        <!-- Côté droit de la page -->
        <div class="col-md-7 card text-bg-secondary mb-3">
            <!-- Ajout d'un document lié au patient -->
            <p> Ajout document : (taille max --> 10 Mo)
            <p>  -  Prescription : (Fichier PDF)

             <!-- Formulaire pour le type de document et le document -->
            <form action='#' method="post"  enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4">
                        <select class="form-select" name='typeDoc'>
                            <option value='prescription'> Prescritpion </option>
                            <option value='photo'> Photo </option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <input class="form-control" type="file" name="userfile" size="50">
                    </div>
                    <div class="col-md-5">
                        
                    </div>
                </div>
                <br>
                 <!-- Bouton pour ajouter le document -->
                <input type="submit" class="btn btn-success" name="submit">

                 <!-- Message d'erreur -->
                <?php echo $_SESSION['message']; ?>
            </form>
            
            

            <?php
                // Enregistrement du fichier dans la base de donnée et dans le dossier local data
                if (isset($_POST["submit"])) {

                    //Rafraichit la page quand le bouton est enclancher
                    header("Location: index.php?page=afficher&lien=".$lienClique."");

                    if (isset($_FILES["userfile"]) && $_FILES["userfile"]["error"] == 0) {
                        $targetDirectory = "data/"; 
                        $typeDoc = $_POST["typeDoc"];
                        $targetFile = $targetDirectory . $instances2[$lienClique][0] . "_" . basename($_FILES["userfile"]["name"]);
                        
                        //Vérification des contraintes et enregistrement de l'erreur dans message
                        if (verificationTaille($_FILES)) {
                            $_SESSION['message'] =  "Erreur : La taille du fichier dépasse la limite autorisée.";

                        } elseif (file_exists($targetFile)) {
                            $_SESSION['message'] =  "Désolé, ce fichier existe déjà.";

                        } elseif ($typeDoc == "prescription" and !estPDF($_FILES)) {
                            $_SESSION['message'] =  "Désolé, une prescription doit être un fichier pdf";

                        } else {
                            if (move_uploaded_file($_FILES["userfile"]["tmp_name"], $targetFile)) {
                                $_SESSION['message'] =  "Le document a été téléchargé avec succès.";

                                $fichier = $instances2[$lienClique][0] . "_" .$_FILES['userfile']['name'];
                                ajoutDocument($connexion, $fichier, $instances2[$lienClique][1], $typeDoc);
                                ajoutHash($connexion, $fichier);

                            } else {
                                $_SESSION['message'] =  "Une erreur s'est produite lors du téléchargement du document.";
                            }
                        }

                    } else {
                        $_SESSION['message'] =  "Erreur : Veuillez sélectionner un document à télécharger.";
                    }
                }
            ?>
            

        </div>

    </div>

    

    

</main>
