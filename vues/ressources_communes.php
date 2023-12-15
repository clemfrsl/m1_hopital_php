<?php 
//définiion des users
define('SERVEUR', 'localhost');
define('UTILISATRICE', 'user1'); // votre login, par exemple p1234567
define('MOTDEPASSE', 'hcetylop'); // votre mot de passe, par exemple abcd1234
define('BDD', 'hopital_php'); // votre BD, par exemple p1234567


//constantes des routes
$routes = array(
	'accueil' => array('vue' => 'recherche_patient'),
	'afficher' => array( 'vue' => 'fiche_patient'),
);

// connexion à la BD, retourne un lien de connexion
function getConnexionBD() {
	$connexion = mysqli_connect(SERVEUR, UTILISATRICE, MOTDEPASSE, BDD);
	if (mysqli_connect_errno()) {
	    printf("Échec de la connexion : %s\n", mysqli_connect_error());
	    exit();
	}
	return $connexion;
}

// déconnexion de la BD
function deconnectBD($connexion) {
	mysqli_close($connexion);
}

// nombre d'instances d'une table $nomTable
function countInstances($connexion, $nomTable) {
	$requete = "SELECT COUNT(*) AS nb FROM $nomTable";
	$res = mysqli_query($connexion, $requete);
	if($res != FALSE) {
		$row = mysqli_fetch_assoc($res);
		return $row['nb'];
	}
	return -1;  // valeur négative si erreur de requête (ex, $nomTable contient une valeur qui n'est pas une table)
}

    
    // retourne les instances d'une table $nomTable
    function getInstances($connexion, $nomTable, $nomColonne) {
        $requete = "SELECT $nomColonne FROM $nomTable ORDER BY $nomColonne";
        $res = mysqli_query($connexion, $requete);
        return mysqli_fetch_all($res, PDO::FETCH_ASSOC);

}

    // Retourne l'instance des critères en donner tous les critères
    function getInstancesAffichage($connexion, $nom, $motif, $pays, $dateMin, $dateMax) {
        // Construction de la clause WHERE en fonction des critères fournis
        $where = "WHERE ";
        if ($nom != "") {
            $where = $where."Nom LIKE '%".$nom."%' and ";
        }
        
        if ($motif != "") {
            $where = $where."Motifs.Libellé = '".$motif. "' and ";
        }
        
        if ($pays != "") {
            $where = $where."Pays.Libellé = '".$pays."' and ";
        }
        
        if ($dateMin != "") {
            $where = $where . "YEAR(DateNaissance) > " . $dateMin . " and ";
        }
        
        if ($dateMax != "") {
            $where = $where . "YEAR(DateNaissance) < " . $dateMax . " and ";
        }
        
        // Suppression du dernier "and" si aucune critères n'est rempli enlever le where
        if ($where == "WHERE "){
            $where = "";
        }
        else{
            $where = substr($where, 0, -5). " ";
        }
    
        // Construction de la requête SQL
        $requete = "SELECT * FROM Patients INNER JOIN Motifs ON Motifs.CodeMotifs =  Patients.CodeMotif INNER JOIN Pays ON Pays.CodePays = Patients.CodePays ".$where."ORDER BY Nom, Prénom";
        
        // Exécution de la requête et retour des résultats
        $res = mysqli_query($connexion, $requete);
        return mysqli_fetch_all($res, PDO::FETCH_ASSOC);
       
    }
    
    //ajout prescription
    function ajoutPrescription($connexion, $fichier, $nom, $type) {
        
        $requeteCodePatient = "SELECT CodePatients FROM Patients WHERE Nom='".$nom."';";
        $resCodePatient = mysqli_query($connexion, $requeteCodePatient);
        $codePatient = mysqli_fetch_all($resCodePatient, PDO::FETCH_ASSOC);
        
        $dateHeure =  date('Y-m-d H:i:s');
        
        $requete = "INSERT INTO Media (CodePatients, DateEnregistrement, TypeMedia, URLMedia) VALUES (".$codePatient[0][0].", '".$dateHeure."', '".$type."', '".$fichier."')";
        $res = mysqli_query($connexion, $requete);
     
        return $res;
    }

    function enregistrerDoc($fichier) {
        return $local='../data';
    }
   
    /*$remote='http://static.ccm2.net/www.commentcamarche.net/_skin/_local/img/logo.png?201007091112';
     
    $DownloadBinaryFile=new DownloadBinaryFile();
    if ($DownloadBinaryFile->load($remote)==TRUE) {
        $DownloadBinaryFile->saveTo($local);
    } else {
        echo 'download failed';
    }*/
?>
